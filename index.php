<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: index.php
                -> Main page
                -> Using font from fonts.google.com
                -> Linked file list
                    -> ./css/main.css
                    -> ./challenge.php
                    -> ./scoreboard.php
                    -> ./login.php
        
        [*] Since 2019.10.31 ~
        
        
        [*] All Reference
            [ㅁ] Create safe PHP CSRF token
                https://stackoverflow.com/questions/6287903/how-to-properly-add-csrf-token-using-php
            [ㅁ] CountDown example
                https://codepen.io/AllThingsSmitty/pen/JJavZN
    */


    // Setting http only
    ini_set( 'session.cookie_secure', 1 );
    session_start();
    
    include "./db.php";
    
    $isAdmin = $mysqli->prepare("SELECT admin FROM user WHERE nickname=?");
    $isAdmin->bind_param("s", $_SESSION['nickname']);
    $isAdmin->execute();
    $isAdmin->bind_result($result);
    $isAdmin->fetch();

    if($result != 1){
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
    <link rel="stylesheet" href="/css/main-countdown.css">
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
                <!-- Page separate -->
                <?php 
                    // Main Page
                    if(!isset($_GET['page'])){
                        include "./db.php";
                        
                        $query = "SELECT begin_timer, begin_timer <= now() as now FROM config";
                        $result = $mysqli->query($query);
                        $row = mysqli_fetch_array($result);
                        
                        if($row['now'] == 1){
                            ?>
                            test<br>
                            test<br>
                            <?php
                        }
                        else{
                            ?>
                            <input type="hidden" class="countDown" value="<?php echo $row['begin_timer']; ?>">
                            <div class="main-countdown">
                                <center>
                                    <br><br>
                                    <h1>Casper CTF 2019</h1>
                                    <ul>
                                        <li><span id="days">--</span>days</li>
                                        <li><span id="hours">--</span>Hours</li>
                                        <li><span id="minutes">--</span>Minutes</li>
                                        <li><span id="seconds">--</span>Seconds</li>
                                    </ul>
                                </center>
                            </div>
                            <?php
                        }
                    }
                    
                    // Rule page
                    else if($_GET['page'] === "rule"){
                        ?>
                        <p>
                            <h1>Rule</h1>
                            1. Do not <font color='red'>attack</font> CTF Server.<br>
                            2. Do not <font color='red'>brute force.</font><br>
                            3. Do not <font color='red'>share Flags or ask for Flags</font>.<br>
                            4. Do not <font color='red'>ruin the fun</font> for players.<br>
                            5. Do not use <font color='red'>Hacking Tools</font> (Are you tool kid...?zz).<br>
                            6. And last... <font color='red'>Just fun :)</font>.
                        </p>
                        <br>
                        <p>
                            <h1>Detail - What is CCTF</h1>
                            <p>CCTF는 Casper Capture The Flag를 줄인 말이다.</p>
                            <p>Capture The Flag란 보안/해킹 쪽에서, 초기 해커들이<br>
                            자발적으로 일종의 실력을 가늠하기 위해 서로의 시스템을 (실제 환경 또는 취약하게 만들어놓고)<br>
                            해킹하여 점수를 얻고 뺏는 방식의 경쟁을 한 것을 말한다.</p>
                        </p>
                        <br>
                        <p>
                            <h1>Error reporting</h1>
                            <p>
                                큰(?)프로젝트를 진행해 본 적은 처음이라 버그가 있을 수 있습니다.<br>
                                버그 또는 기타 등 문제 문의는 Universe에게 알려 주시면 감사하겠습니다(꾸벅).
                            </p>
                        </p>
                        <?php
                        
                    }
                    
                    // Rank page
                    else if($_GET['page'] === "rank"){
                        
                    }
                    
                    else{
                        header("Location: /",true,301);
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="footer">
        Casper Capture The Flag<br>
        Designed by <a href="https://profile.lactea.kr" target="_blank">universe</a>
    </div>
    <script src="/js/main-countdown.js"></script>
</body>
</html>