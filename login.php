<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: login.php
                
                -> Login page
                -> Logout page
                -> Register and File upload page
                
                -> Using font from fonts.google.com
                -> Linked file list
                    -> ./css/main.css
                    -> ./css/login.css
                    -> ./css/etc.css
                    -> ./scoreboard.php
                    -> ./index.php
                    -> ./challenge.php
        
        [*] Since 2019.10.31 ~
        
        
    */
    
    // Setting http only
    ini_set( 'session.cookie_secure', 1 );
    session_start();
    
    include "./db.php";
    
    $config_query = "SELECT login, registration FROM config";
    $config_result = $mysqli->query($config_query);
    while($config = mysqli_fetch_array($config_result)){
        if($config['login'] != 1 && $_GET['page'] == "login"){
            echo "<script>alert('현재 로그인 할 수 없습니다.'); location.href='/';</script>";
            exit();
        }
        if($config['registration'] != 1 && $_GET['page'] == "register"){
            echo "<script>alert('현재 가입을 할 수 없습니다.'); location.href='/';</script>";
            exit();
        }
    }
    
    // Generate random directory name
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
    
    
    
    if (!isset($_GET['page'])){
        echo "<script>location.href='./login.php?page=login'</script>";
        exit();
    }
    
    // Logout
    if($_GET['page'] === "logout"){
        if($_SESSION['isLogin'] === 1){
            session_destroy();
            echo "<script>alert('로그아웃 되었습니다.'); window.location.replace('/');</script>";   
        }
        else{
            echo "<script>alert('로그인 상태가 아닙니다.'); window.location.replace('/login.php'); </script>";
        }
    }
    
    // Login check
    if($_SESSION['isLogin']){
        echo "<script>alert('이미 로그인 된 상태 입니다.'); window.location.replace('/');</script>";
    }
    
    // Login part
    if ($_GET['page'] === "login" && isset($_POST['userid'], $_POST['userpw'])){
        
        $userid = $_POST['userid'];
        $userpw = $_POST['userpw'];
        
        // Start login query
        if($result = $mysqli->prepare("SELECT userid, userpw, nickname, admin FROM user WHERE userid = ?")){
            $result->bind_param('s', $userid);
            $result->execute();
            $result->store_result();
            
            if($result->num_rows > 0){
                $result->bind_result($id,$pw,$nickname, $admin);
                $result->fetch();
                
                // Login success
                if(password_verify($userpw, $pw)){
                    $_SESSION['nickname'] = $nickname;
                    $_SESSION['userid'] = $id;
                    $_SESSION['isLogin'] = 1;
                    $_SESSION['admin'] = $admin;
                    echo "<script>window.location.replace('/');</script>";
                    exit();
                }
                
                // Login fail
                else{
                    echo "<script>alert('아이디 혹은 비밀번호가 틀렸습니다.'); history.back();</script>";
                    exit();
                }
            }
            // Login fail
            else{
                echo "<script>alert('아이디 혹은 비밀번호가 틀렸습니다.'); history.back();</script>";
                exit();
            }
            $result->close();
        }
        else{
            echo "<script>alert('예상치 못한 에러가 발생했습니다.');window.location.replace('/login.php');</script>";
            exit();
        }
    }
    
    // Register
    if($_GET['page'] === "register" && isset($_POST['nickname'], $_POST['userid'], $_POST['userpw1'], $_POST['userpw2'])){
        include "./db.php";
        /* Length range information
            * nickname: 5 ~ 14
            * userid: 3 ~ 30
            * userpw1: 8 ~ 30
            * userpw2: 8 ~ 30
            * comment: ~ 500
            * File name: 1 ~ 100
        */
        $nickname = 5<=strlen($_POST['nickname']) && strlen($_POST['nickname'])<=14 ? $_POST['nickname'] : false;
        $userid = 3<=strlen($_POST['userid']) && strlen($_POST['userid'])<=30 ? $_POST['userid'] : false;
        $userpw1 = 8<=strlen($_POST['userpw1']) && strlen($_POST['userpw1'])<=30 ? $_POST['userpw1'] : false;
        $userpw2 = 8<=strlen($_POST['userpw2']) && strlen($_POST['userpw2'])<=30 ? $_POST['userpw2'] : false;
        $comment = strlen($_POST['comment'])<=500 ? $_POST['comment'] : false;
        
        
        if(preg_match('/[^A-Za-z0-9]/', $nickname) || preg_match('/[^A-Za-z0-9]/', $userid)){
            echo "<script>alert('ID와 nickname은 영문, 숫자만 가능합니다.'); history.back();</script>";
                exit();
        }
        
        // Verify that value is correct. 
        if($nickname && $userid && $userpw1 && $userpw2 && ($comment || empty($_POST['comment']))){
            // password check
            if($userpw1 !== $userpw2){
                echo "<script>alert('입력한 패스워드가 같지 않습니다.'); history.back();</script>";
                exit();
            }

            // Nickname check
            if($result = $mysqli->prepare("SELECT nickname FROM user WHERE nickname = ?")){
                $result->bind_param('s', $nickname);
                $result->execute();
                $result->store_result();
                
                if($result->num_rows > 0){
                    echo "<script>alert('입력한 닉네임은 이미 존재 합니다.'); location.href='./login.php?page=register'; </script>;";
                    exit();
                }
                else{
                    $result->close();
                    
                    // ID check
                    if($result = $mysqli->prepare("SELECT userid FROM user WHERE userid = ?")){
                        $result->bind_param('s', $userid);
                        $result->execute();
                        $result->store_result();
                        
                        if($result->num_rows > 0){
                            echo "<script>alert('입력한 아이디는 이미 존재 합니다.'); location.href='./login.php?page=register';</script>";
                            exit();
                        }
                        
                        else{
                            $result->close();
                            
                            // Secure file upload
                            // Reference: https://codereview.stackexchange.com/questions/196656/upload-image-using-php-7-best-practices-to-make-it-secure
                            //            https://cloudinary.com/blog/file_upload_with_php
                            if(isset($_FILES['profile_img'])){
                                $fileName = $_FILES['profile_img']['name'];
                                
                                // Check file name length
                                if(strlen($fileName) >= 1 && strlen($fileName) <= 100){
                                    
                                    // Check file extension
                                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                                    $fileExtension = explode('.',$fileName);
                                    $fileExtension = strtolower(end($fileExtension));
                                    if (in_array($fileExtension, $allowed)) {
                                        
                                        // Check file size
                                        $fileSize = $_FILES['profile_img']['size'];
                                        if($fileSize <= 2000000){
                                            $currentDir  = getcwd();
                                            $uploadDir = "/uploads/userImage/";
                                            $fileTmpName  = $_FILES['profile_img']['tmp_name'];
                                            $randomString = generateRandomString(32) . "/";
                                            $fileName = str_replace("..", "", $fileName);
                                            $fileName = str_replace("/", "", $fileName);
                                            
                                            $uploadPath = $currentDir . $uploadDir . $randomString . basename($fileName);
                                            
                                            // chown -R www-data ./uploads/
                                            if(!is_writeable("." . $uploadDir)){
                                                echo "<script>alert('".$uploadDir." 디렉토리에 쓰기 권한이 없습니다. 권한을 수정 부탁드립니다.'); history.back();</script>";
                                                exit();
                                            }
                                            // Make dir
                                            mkdir("." . $uploadDir . $randomString, 0755);
                                            if(!is_dir("." . $uploadDir . $randomString)){
                                                echo "<script>alert('디렉토리를 생성하는데 문제가 발생했습니다. 관리자에게 문의 바랍니다.'); history.back();</script>";
                                                exit();
                                            }
                                            
                                            // File upload
                                            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                                            // Check that file is successfully uploaded.
                                            if($didUpload){
                                                // Register 
                                                if($insert = $mysqli->prepare("INSERT INTO user (userid, userpw, nickname, points, admin, visible, comment, profile) VALUES(?,?,?,?,?,?,?,?)")){
                                                    $password = password_hash($userpw1, PASSWORD_DEFAULT);
                                                    $point = 0;
                                                    $admin = 0;
                                                    $visible = 1;
                                                    $upload_path = $uploadDir . $randomString . $fileName;
                                                    
                                                    $insert->bind_param("sssiiiss", $userid, $password, $nickname, $point, $admin, $visible, $comment, $upload_path);
                                                    $insert->execute();
                                                    $insert->close();
                                                    echo "<script>alert('성공적으로 등록이 되었습니다.'); location.href='./login.php?page=login'; </script>";
                                                }
                                                else{
                                                    echo "error1";
                                                    exit();
                                                }
                                            }
                                            else{
                                                echo "<script>alert('이미지 업로드 시 에러가 발생했습니다. 관리자에게 문의 바랍니다.'); history.back();</script>";
                                                exit();
                                            }
                                        }
                                        else{
                                            echo "<script>alert('파일 크기는 2MB까지 업로드 가능합니다.'); history.back();</script>";
                                            exit();
                                        }
                                        
                                    }
                                    else{
                                        echo "<script>alert('확장자는 jpg, jpeg, png, gif만 업로드 가능합니다.'); history.back();</script>";
                                        exit();
                                    }
                                }
                                else{
                                    echo "<script>alert('파일 이름 길이는 1 ~ 100글자 까지 입니다.'); history.back();</script>";
                                    exit();
                                }
                            }
                            else{
                                echo "<script>alert('이미지를 등록 해주세요.'); history.back();</script>";
                                exit();
                            }
                        }
                    }
                    else{
                        echo "error2";
                        exit();
                    }
                }
            }
            else{
                echo "error3";
                exit();
            }
        }
        else{
            echo "<script>alert('입력 양식을 지켜주세요.'); location.href='./login.php?page=register'; </script>";
            exit();
        }
    }
?>

<html>
<html lang="ko">
    <title>
        CCTF :: Login
    </title>
    <!-- Font Link: https://fonts.google.com/?selection.family=Source+Code+Pro#QuickUsePlace:quickUse%2FFamily:Roboto -->
    <link href="//fonts.googleapis.com/css?family=Source+Code+Pro|Titillium+Web:300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" />
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/etc.css">
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
            <div class="login-title">
            <?php
                if($_GET['page'] === "login"){ ?><h1>Login</h1><?php }
                else if($_GET['page'] === "register"){ ?><h1>Register</h1><?php } ?>
            </div>
            <div class="login-content">
                <?php
                    if ($_GET['page'] == "login"){?>
                        <br><br>
                        <form action="./login.php?page=login" method="post">
                            <center>
                                <table>
                                    <tr>
                                        <td width="50">
                                            <i class="fas fa-address-card fa-2x"></i>
                                            <br><br><br>
                                        </td>
                                        <td>
                                            <div class="group">
                                              <input type="text" name="userid" required>
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>ID</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fas fa-key fa-2x"></i>
                                            <br><br><br>
                                        </td>
                                        <td>
                                            <div class="group">      
                                              <input type="password" name="userpw" required>
                                              <span class="highlight"></span>
                                              <span class="bar"></span>
                                              <label>Password</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:right">
                                            <button type="submit" class="bnt">Login</button>
                                        </td>  
                                    </tr>
                                </table>
                            </center>
                        </form>
                    <?php }
                    else if ($_GET['page'] == "register"){?>
                        <br><br>
                        <form action="./login.php?page=register" method="post" enctype="multipart/form-data">
                            <center>
                            <table>
                                <tr>
                                    <td width="50">
                                        <i class="fas fa-user fa-2x"></i>
                                        <br><br><br>
                                    </td>
                                    <td>
                                        <div class="group">
                                          <input type="text" name="nickname" minlength="1" maxlength="14" required>
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>Nickname</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-address-card fa-2x"></i>
                                        <br><br><br>
                                    </td>
                                    <td>
                                        <div class="group">
                                          <input type="text" name="userid" minlength="3" maxlength="30" required>
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>ID</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-key fa-2x"></i>
                                        <br><br><br>
                                    </td>
                                    <td>
                                        <div class="group">      
                                          <input type="password" name="userpw1" minlength="8" maxlength="30" required>
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>Password</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-key fa-2x"></i>
                                        <br><br><br>
                                    </td>
                                    <td>
                                        <div class="group">      
                                          <input type="password" name="userpw2" minlength="8" maxlength="30" required>
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>Password confrim</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="far fa-comment-dots fa-2x"></i>
                                        <br><br><br>
                                    </td>
                                    <td>
                                        <div class="group">
                                          <input type="text" name="comment" maxlength="500">
                                          <span class="highlight"></span>
                                          <span class="bar"></span>
                                          <label>Comments (not required)</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                          <input type="file" name="profile_img" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right; height: 100px">
                                        <button type="submit" class="bnt">Register</button>
                                    </td>  
                                </tr>
                            </table>
                            </center>
                        </form>
                    <?php } ?>
                </div>
        </div>
    </div>
    <div id="footer">
        Casper Capture The Flag<br>
        Designed by <a href="https://profile.lactea.kr" target="_blank">universe</a>
    </div>
</body>
</html>