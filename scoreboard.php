<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: scoreboard.php
                -> Scoreboard page
                -> Using font from fonts.google.com
                -> Using jquery CDN from code.jquery.com
                -> Linked file list
                    -> ./css/main.css
                    -> ./css/scoreboard.css
                    -> ./css/bootstrap/bootstrap.css
                    -> ./js/bootstrap/bootstrap.js
                    -> ./challenge.php
                    -> ./index.php
                    -> ./login.php
        
        [*] Since 2019.10.31 ~
    
    */
    
    // Setting http only
    ini_set( 'session.cookie_secure', 1 );
    session_start();
    
    include "./db.php";
    
    $query = "SELECT nickname, points, comment, profile, last_time, solved_challenge FROM user WHERE visible = 1 ORDER BY points DESC";
    $result = $mysqli->query($query);
    
    $isAdmin = $mysqli->prepare("SELECT admin FROM user WHERE nickname=?");
    $isAdmin->bind_param("s", $_SESSION['nickname']);
    $isAdmin->execute();
    $isAdmin->bind_result($a);
    $isAdmin->fetch();

    if($a != 1){
        $admin = 0;
    }
    else{
        $admin = 1;
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
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/scoreboard.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.css">
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
                    if($admin == 1){
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
            <div class="content-value">
                <h1>Team Scoreboard</h1>
                <center>
                    <!-- Introduce Top3 -->
                    <?php
                        $count = 1;
                        while($count < 4){
                            if($user_info = mysqli_fetch_array($result)){
                                ?>
                                <div class="scoreboard-top3-box">
                                    <img src="/images/<?php echo $count ?>.png" width="150" class="scoreboard-top3-image-front">
                                    <img src="<?php echo htmlspecialchars($user_info['profile']); ?>" width="150" class="scoreboard-top3-image-back">
                                    <!--<img src="https://i.pinimg.com/474x/e2/04/c0/e204c068b13c23726be7fdf5dd0e67c8.jpg" width="150" class="scoreboard-top3-image-back">-->
                                    <div class="scoreboard-top3-footer-<?php echo $count ?>">
                                        <span class="scoreboard-top3-<?php echo $count ?>"><?php echo $count ?></span><span class="scoreboard-top3-<?php echo $count ?>-s">st</span>
                                        <span class="scoreboard-top3-points"><?php echo $user_info['points']; ?></span> pts
                                        <br>
                                        <span class="scoreboard-top3-nickname"><span class="scoreboard-top3-<?php echo $count ?>-s"><?php echo htmlspecialchars($user_info['nickname']); ?></span></span>
                                    </div>
                                </div>
                                
                                <?php
                            }
                            else{
                                ?>
                                <div class="scoreboard-top3-box">
                                    <img src="/images/3.png" width="150" class="scoreboard-top3-image-front">
                                    <img src="/images/questionPerson.png" width="150" class="scoreboard-top3-image-back">
                                    <div class="scoreboard-top3-footer-<?php echo $count ?>">
                                        <span class="scoreboard-top3-<?php echo $count ?>"><?php echo $count ?></span><span class="scoreboard-top3-<?php echo $count ?>-s">st</span>
                                        <span class="scoreboard-top3-points">???</span> pts
                                        <br>
                                        <span class="scoreboard-top3-nickname"><span class="scoreboard-top3-<?php echo $count ?>-s">????</span></span>
                                    </div>
                                </div>
                                <?php
                            }
                            $count++;
                        }
                    ?>
                    <br><br><br><br>
                    
                    <!-- Introduce other user -->
                    <?php
                        if($result->num_rows > 0){
                            $count = 4;
                            ?>
                            <table class="table table-striped table-dark">
                                <thead>
                                    <tr>
                                      <th scope="col" style="width:100px; text-align: center;">#</th>
                                      <th scope="col" style="width: 200px;">Nickname</th>
                                      <th scope="col" style="width: 150px;">Score</th>
                                      <th scope="col">Comments</th>
                                      <th scope="col" style="width:300px;">Last time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    while($userinfo = mysqli_fetch_array($result)){
                                        ?>
                                        <tr>
                                          <th scope="row" style="text-align: center;"><?php echo $count; ?></th>
                                          <td><?php echo htmlspecialchars($userinfo['nickname']); ?></td>
                                          <td><?php echo $userinfo['points']; ?></td>
                                          <td class="scoreboard-text-overflow"><?php echo htmlspecialchars($userinfo['comment']); ?></td>
                                          <td><?php echo $userinfo['last_time']; ?></td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                        }
                    ?>
                </center>
            </div>
        </div>
    </div>
    <div id="footer">
        Casper Capture The Flag<br>
        Designed by <a href="https://profile.lactea.kr" target="_blank">universe</a>
    </div>
    <script src="/js/bootstrap/bootstrap.js"></script>
</body>
</html>