<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: challenge.php
                
                -> Challenge page
                    Show challenge list
                
                -> Using font from fonts.google.com
                -> Using jquery CDN from code.jquery.com
                -> Linked file list
                    -> ./css/main.css
                    -> ./css/challenge.css
                    -> ./css/etc.css
                    -> ./css/bootstrap/bootstrap.css
                    -> ./js/bootstrap/bootstrap.js
                    -> ./scoreboard.php
                    -> ./index.php
                    -> ./login.php
        
        [*] Since 2019.10.31 ~
    */

    // Setting http only
    ini_set( 'session.cookie_secure', 1 );
    session_start();
    
    // User is not logined.
    if(!$_SESSION['isLogin']){
        header("Location: /login.php?page=login", true, 301);
    }
    
    // User is logined. 
    include "./db.php";
    
    /////////////////////////////
    // CCTF가 시작했는지 확인
    $query = "SELECT begin_timer <= now() as now FROM config";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_array($result);
    
        // 1이면 시작
    if($row['now'] != 1){
        header("Location: /", true, 301);
    }
    /////////////////////////////
    
    
    // Get prob category
    $category_query = "SELECT category_name FROM category";
    $category_result = $mysqli->query($category_query);
    
    // Create sure PHP CSRF
    if(empty($_SESSION['csrf_token']) || $_SESSION['csrf_token_lifeline'] < time()){
        $_SESSION['csrf_token_lifeline'] = time() + 3600;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    
    $isAdmin = $mysqli->prepare("SELECT admin FROM user WHERE nickname=?");
    $isAdmin->bind_param("s", $_SESSION['nickname']);
    $isAdmin->execute();
    $isAdmin->bind_result($a);
    $isAdmin->fetch();

    if($a != 1){
        $admin_ok = 0;
    }
    else{
        $admin_ok = 1;
    }
    $isAdmin->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <title>
        CCTF :: Casper Capture The Flag
    </title>
    <!-- Font Link: https://fonts.google.com/?selection.family=Source+Code+Pro#QuickUsePlace:quickUse%2FFamily:Roboto -->
    <link href="//fonts.googleapis.com/css?family=Source+Code+Pro|Titillium+Web:300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" />
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/challenge.css">
    <link rel="stylesheet" href="/css/etc.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="/css/challenge-countdown.css">
    <!-- CDN -->
    <script src="//cdn.jsdelivr.net/npm/vue"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
</head>
<body>
    <div id="container">
        <nav class="navigation">
            <a href="/"><span class="navigation-menu">CCTF</span></a>
            <a href="/?page=rule"><span class="navigation-menu">Rules and Details</span></a>
            <a href="/challenge.php"><span class="navigation-menu">Let's Play!!</span></a>
            <a href="/scoreboard.php"><span class="navigation-menu">Scoreboard</span></a>
            <?php
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 1){
                    ?>
                    <a href="/login.php?page=logout"><span class="navigation-menu-right">Logout</span></a>
                    <?php
                    if($admin_ok == 1){
                        ?>
                        <span class="navigation-menu-right">&nbsp;&nbsp;<a href="/admin">Go to admin page</a></span>
                        <?php
                    }
                    else{
                        ?>
                        <span class="navigation-menu-right">Wellcome &nbsp;&nbsp;<a href="/?page=profile"><?php echo htmlspecialchars($_SESSION['nickname']); ?></a></span>                        
                        <?php
                    }
                    ?>
                    <?php
                }
                else{
                    ?>
                    <a href="/login.php?page=login"><span class="navigation-menu-right">Login</span></a>
                    <a href="/login.php?page=register"><span class="navigation-menu-right">Register</span></a>
                    <?php
                }
            ?>
        </nav>
        <header class="header">
            
        </header>
        <div class="content">
            <div class="prob-right">
                <div class="prob-option">
                    <select class="browser-default custom-select challenge-category-option" >
                        <option value="" selected>All</option>
                        <?php
                            $category_query = "SELECT * FROM category";
                            $result2 = $mysqli->query($category_query);
                                    
                            while ($category = mysqli_fetch_array($result2)){
                            ?>
                                <option value="<?php echo strtolower($category['category_name']); ?>"><?php echo strtolower($category['category_name']); ?></option>
                            <?php
                            }
                            ?>
                    </select>
                </div>
                <!--<div class="prob-option prob-option-select">-->
                <!--    <select class="browser-default custom-select challenge-view-option">-->
                <!--        <option value="" selected>All</option>-->
                <!--        <option value="not-solved">Not solved</option>-->
                <!--        <option value="solved">Solved</option>-->
                <!--    </select>-->
                <!--</div>-->
            </div>
            <div class="content-value">
                <!-- Challenge list -->
                <?php
                    $count = 1;
                    $is_solved = 0;
                    
                    while($category_name = mysqli_fetch_array($category_result)){
                        $prob_query = "SELECT idx, title, contents, points, attach, category, solved, first_solver, level, solver_list FROM challenge WHERE visible = 1 AND category = '".$category_name['category_name']."'";
                        $prob_result = $mysqli->query($prob_query);
                        if($prob_result->num_rows > 0){ ?>
                            <br>
                            <div class="challenge-list">
                            <h1 class="challenge-category"><?php echo strtoupper($category_name['category_name']); ?></h1><?php
                            while($prob_list = mysqli_fetch_array($prob_result)){
                                $solver_list = explode(",", $prob_list['solver_list']);
                                if(in_array($_SESSION['nickname'], $solver_list)){
                                    $is_solved = 1;
                                }
                                ?>
                                <!-- Box -->
                                <div class="prob" data-toggle="modal" data-target="#prob<?php echo $count; ?>" id="idx_<?php echo $prob_list['idx']; ?>" <?php if(count($solver_list) > 1 && $is_solved == 0){ echo "style= 'background: #777'"; }  if($is_solved == 1){ echo "style='background: green;'"; } ?>>
                                    <div class="prob-content">
                                        <div class="prob-title">
                                            <div class="challenge-search" style="display: none">
                                                <?php echo strtolower($category_name['category_name']) ?>
                                            </div>
                                            <?php echo htmlspecialchars($prob_list['title']); ?>
                                        </div>
                                        <div class="prob-points">
                                            <?php echo $prob_list['points']; ?>
                                        </div>
                                        <font size="3"><strong>pts</strong></font>
                                        <div class="prob-level">
                                            <?php 
                                                if($prob_list['level'] == "easy"){
                                                    echo strtoupper($prob_list['level']);
                                                }
                                                else if($prob_list['level'] == "normal"){
                                                    ?>
                                                    <font color="#d46313"><?php echo strtoupper($prob_list['level']); ?></font>
                                                    <?php
                                                }
                                                else if($prob_list['level'] == "hard"){
                                                    ?>
                                                    <font color="red"><?php echo strtoupper($prob_list['level']); ?></font>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="prob<?php echo $count; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <?php echo htmlspecialchars($prob_list['title']); ?>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <?php echo $prob_list['contents']; ?>
                                        <div class="modal-solver" data-toggle="modal" data-target="#prob<?php echo $count; ?>_test">
                                            Solver: <?php echo $prob_list['solved']; ?> (Click)
                                        </div>
                                        <?php
                                            if($admin_ok == 1){
                                                ?>
                                                <br>
                                                <a href="/admin/?page=challenge#challenge-<?php echo $prob_list['idx']; ?>" class="modal-href">Modify this challenge.</a>
                                                <?php
                                            }
                                        ?>
                                      </div>
                                      <div class="modal-footer">
                                        Flag:&nbsp;&nbsp;&nbsp; <input type="text" class="form-control" id="recipient-name" placeholder="Input Flag">
                                        <input type="hidden" class="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                                        <button type="button" class="btn btn-primary submit-flag" value="<?php echo $prob_list['idx']; ?>">Submit</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                
                                <!-- Solver list -->
                                <div class="modal fade" id="prob<?php echo $count; ?>_test" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        Solver List
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <table class="table"  style="width: 460px;">
                                          <thead class="thead-dark">
                                            <tr>
                                              <th scope="col" style="width: 40px;">#</th>
                                              <th scope="col" style="width: 200px;">Nickname</th>
                                              <!--<th scope="col" style="width: 250px;">Last time</th>-->
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <?php
                                            for($j = 0; $j<count($solver_list) - 1; $j++){
                                                ?>
                                                <tr>
                                                  <th scope="row"><?php echo $j + 1; ?></th>
                                                  <td><?php echo htmlspecialchars($solver_list[$j]); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            
                                          ?>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                
                                <?php
                                $count++;
                                $is_solved = 0;
                            }
                            ?>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div id="realtime">
        <!-- 실시간 채팅 -->
        <!--<div class="hr"></div>-->
        <!--<div class="realtime-content realtime-chatting">Chatting<i class="fas fa-list"></i></div>-->
        <!--<div class="hr"></div>-->
        <!--<div class="realtime-chatting-list" style="display: none">-->
        <!--</div>-->
        <!---->
        
        <!-- 실시간 공지 -->
        <div class="hr"></div>
        <div class="realtime-content realtime-announcment">Announcement<i class="fas fa-list"></i></div>
        <div class="hr"></div>
        <div class="realtime-announcment-list" style="display: none">
            <?php
                $query = "SELECT idx, category, message, date_format(date, '%m-%d %H:%i') as date FROM announcement ORDER BY idx DESC";
                $result = $mysqli->query($query);
                
                while($row = mysqli_fetch_array($result)){
                    ?>
                    <div class="announcement-date" id="announce_<?php echo $row['idx']; ?>">
                        <?php echo "[" . $row['date'] . "]"; ?>
                    </div><?php
                    if($row['category'] === "admin"){   // admin이 직접 공지 할 경우
                        ?>
                        <div class="announcement-content">
                            <?php 
                                echo "<font color='#d46313'>[Notice]</font>: ";
                                echo htmlspecialchars($row['message']); ?>
                        </div>
                        <?php
                    }
                    else if($row['category'] === "captured"){   // user에 대한 이벤트 (문제를 풀었을 때 등등..)
                        ?>
                        <div class="announcement-content">
                            <?php 
                                $message = explode(" || ", $row['message']);
                                
                                echo "<font color='red'>".htmlspecialchars($message[0])."</font> captured <font color='#d46313'>" . htmlspecialchars($message[1]) . "</font>";
                            ?>
                        </div>
                        <?php
                    }
                    else if($row['category'] === "challenge"){      // challenge 문제가 수정, visible, hidden 상태로 될때 
                        ?>
                        <div class="announcement-content">
                            <?php 
                                echo "<font color='#d46313'>[Updated]</font>: ";
                                echo htmlspecialchars($row['message']); ?>
                        </div>
                        <?php
                    }
                    ?>
                    <br>
                    <?php
                }
            
            ?>
        </div>
        <!---->
        
        <!-- Countdown -->
        <!-- Countdown disign list -->
        <!--
            https://bashooka.com/coding/40-css-javascript-animated-countdown-timer-examples/
            https://codepen.io/shshaw/pen/BzObXp
            https://codepen.io/lawrencealan/pen/cdwhm
            https://codepen.io/gau/pen/LjQwGp
            https://codepen.io/cMack87/pen/rVmEQm
        -->
        <?php
            // $query = "select TIMESTAMPDIFF(second, date_format(now(), '%Y-%m-%d %H:%i:%s'), date_format(end_timer, '%Y-%m-%d %H:%i:%s')) as date from config";
            $query = "SELECT end_timer, end_timer <= now() as now FROM config";
            $result = $mysqli->query($query);
            $row = mysqli_fetch_array($result);
            
             if($row['now'] == 1){
                 $row['end_timer'] = 0;
             }
        ?>
        <div class="hr"></div>
        <div class="realtime-content realtime-countdown">Timer<i class="fas fa-list"></i></div>
        <div class="hr"></div>
        <div class="realtime-countdown-list" style="display: none"> 
            <div id="clock">
                <input type="hidden" class="countDown" value="<?php echo $row['end_timer']; ?>">
                <!--<p class="date">{{ date }}</p>-->
                <p class="time">{{ date }} days {{ time }}</p>
                <!--<p class="text">DIGITAL CLOCK with Vue.js</p>-->
            </div>
        </div>
        <!---->
    </div>
    
    
    <div id="footer">
        Casper Capture The Flag<br>
        Designed by <a href="https://profile.lactea.kr" target="_blank">universe</a>
    </div>
    <script src="/js/bootstrap/bootstrap.js"></script>
    <script src="/js/requestFlag.js"></script>
    <script src="/js/challenge.js"></script>
    <script src="/js/challenge-countdown.js"></script>
    <!--<script src="/js/etc.js"></script>-->
</body>
</html>
