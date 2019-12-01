<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: index.php
                -> Admin page
                -> Using font from fonts.google.com
                -> Linked file list
                    -> /css/main.css
                    -> /challenge.php
                    -> /scoreboard.php
                    -> /login.php
        
        [*] Since 2019.10.31 ~
        
        
        [*] All Reference
            [ㅁ] Create safe PHP CSRF token
                https://stackoverflow.com/questions/6287903/how-to-properly-add-csrf-token-using-php
            [ㅁ] bootstrap datetimepicker 
                https://www.malot.fr/bootstrap-datetimepicker/demo.php
            [ㅁ] bootstrap form example
                https://getbootstrap.com/docs/4.0/components/forms/
            [ㅁ] facebook admin page example
                https://github.com/facebook/fbctf/wiki/Admin-Guide
    */


    // Setting http only
    ini_set( 'session.cookie_secure', 1 );
    session_start();
    
    include "../db.php";
    
    $isAdmin = $mysqli->prepare("SELECT admin FROM user WHERE nickname=?");
    $isAdmin->bind_param("s", $_SESSION['nickname']);
    $isErr = $isAdmin->execute();
    $isAdmin->bind_result($a);
    $isAdmin->fetch();
    
    if($isErr === false){
        ?>
        <script>alert("관리자 확인 중 DB 에러."); location.href="/";</script>
        <?php
    }
    if($a != 1){
        ?>
        <script>location.href="/";</script>
        <?php
    }
    $isAdmin->close();
    
    // Create sure PHP CSRF
    if(empty($_SESSION['csrf_token']) || $_SESSION['csrf_token_lifeline'] < time()){
        $_SESSION['csrf_token_lifeline'] = time() + 3600;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
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
    <link rel="stylesheet" href="/css/admin/admin-main.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.css">
    <script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="/js/bootstrap/bootstrap.js"></script>
    <link rel="stylesheet" href="/css/admin/datepicker/bootstrap-datetimepicker.css">
    <link rel="stylesheet" href="/css/admin/datepicker/bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="/js/admin/datepicker/bootstrap-datetimepicker.js"></script>
    <script type='text/javascript' src="/js/admin/datepicker/bootstrap-datetimepicker.min.js"></script>
    <script type='text/javascript' src="/js/admin/datepicker/bootstrap-datetimepicker.ko.js"></script>
</head>
<body>
    <div id="container">
        <nav class="navigation">
            <a href="/"><span class="navigation-menu">CCTF</span></a>
            <a href="./?page=configuration"><span class="navigation-menu">Config</span></a>
            <a href="./?page=announcement"><span class="navigation-menu">Announcements</span></a>
            <a href="./?page=challenge"><span class="navigation-menu">Challenges</span></a>
            <a href="./?page=category"><span class="navigation-menu">Categories</span></a>
            <a href="./?page=user"><span class="navigation-menu">Users Info</span></a>
            <a href="./?page=log"><span class="navigation-menu">Logs</span></a>
            <!--<span class="navigation-menu-right">&nbsp;&nbsp;<a href="/admin">Game Start</a></span>-->
        </nav>
        <header class="header">
            
        </header>
        <div class="content">
            <div class="content-value">
                <?php 
                    if($_GET['page'] === "configuration"){
                        $config_query = "SELECT * FROM config";
                        $config_result = $mysqli->query($config_query);
                        
                        while($config = mysqli_fetch_array($config_result)){
                            $config_login = $config['login'];
                            $config_register = $config['registration'];
                            $config_begin_timer = $config['begin_timer'];
                            $config_end_timer = $config['end_timer'];
                            $config_game_start = $config['game_start'];
                        }
                        
                        ?>
                        <div class="config-box">
                            <div class="config-title">
                                LOGIN
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                <label class="mr-sm-2" for="inlineFormCustomSelect">Login abled / disabled</label>
                                <br>
                                <div class="custom-control custom-radio custom-control-inline">
                                   <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" value="configuration-login-on" <?php if($config_login == 1){ echo "checked"; } ?>>
                                   <label class="custom-control-label" for="customRadioInline1">ON</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                   <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input" value="configuration-login-off" <?php if($config_login == 0){ echo "checked"; } ?>>
                                   <label class="custom-control-label" for="customRadioInline2">OFF</label>
                                </div>
                                <div class="config-request-result"></div>
                            </div>
                        </div>
                        <div class="config-box">
                            <div class="config-title">
                                REGISTRATION
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                <label class="mr-sm-2" for="inlineFormCustomSelect">Registration abled / disabled</label>
                                <br>
                                <div class="custom-control custom-radio custom-control-inline">
                                   <input type="radio" id="customRadioInline3" name="customRadioInline3" class="custom-control-input" value="configuration-register-on" <?php if($config_register == 1){ echo "checked"; } ?>>
                                   <label class="custom-control-label" for="customRadioInline3">ON</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                   <input type="radio" id="customRadioInline4" name="customRadioInline3" class="custom-control-input" value="configuration-register-off" <?php if($config_register == 0){ echo "checked"; } ?>>
                                   <label class="custom-control-label" for="customRadioInline4">OFF</label>
                                </div>
                                <div class="config-request-result"></div>
                            </div>
                        </div>
                        <div class="config-box">
                            <div class="config-title">
                                TIMER
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                <table>
                                    <td style="width: 200px;">
                                        <label class="mr-sm-2" for="inlineFormCustomSelect">Begin time</label>
                                        <br>
                                        <div class="input-append date begin_datetime">
                                            <input id="begin_datetime" size="16" type="text" value="<?php echo $config_begin_timer; ?>" readonly style="width: 180px;" placeholder="Click to set begin time">
                                            <span class="add-on"><i class="icon-th"></i></span>
                                        </div>
                                    </td>
                                    <td style="width: 200px;">
                                        <label class="mr-sm-2" for="inlineFormCustomSelect">End time</label>
                                        <br>
                                        <div class="input-append date end_datetime">
                                            <input id="end_datetime" size="16" type="text" value="<?php echo $config_end_timer; ?>" readonly style="width: 180px;" placeholder="Click to set end time">
                                            <span class="add-on"><i class="icon-th"></i></span>
                                        </div>
                                    </td>
                                    <td>
                                        <Br>
                                        <button type="button" class="btn btn-outline-success" id="config-timer">Submit</button>
                                    </td>
                                    <td>
                                        <Br>
                                        <div class="config-request-result"></div>
                                    </td>
                                </table>
                                <script type="text/javascript">
                                    $(".begin_datetime").datetimepicker({
                                        format:"yyyy-mm-dd hh:ii",
                                        autoclose: true,
                                        todayBtn: true,
                                        pickerPosition: "bottom"
                                    });
                                    $(".end_datetime").datetimepicker({
                                        format:"yyyy-mm-dd hh:ii",
                                        autoclose: true,
                                        todayBtn: true,
                                        // startDate: $("#begin_datetime").val(),
                                        pickerPosition: "bottom"
                                    });
                                </script> 
                            </div>
                        </div>
                        <div class="config-box">
                            <div class="config-title">
                                Reset CCTF
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                All record is reset.<br>
                                Example, <font color="#d46313">User Infomation</font> is reset to below.<br>
                                --> points, last_time, solved challenge<br><Br>
                                <font color="#d46313">Announcement</font> is reset to below.<br>
                                --> idx, Message, date<br><Br>
                                <font color="#d46313">Challenge</font> is reset to below.<br>
                                --> solved, first_solver, solver_list<br><Br>
                                <font color="#d46313">Config</font> is reset to below.<br>
                                --> Start time, End time<br><Br>
                                <button type="button" class="btn btn-outline-danger config-reset">Reset CCTF</button>
                                <div class="config-request-result"></div>
                            </div>
                        </div>
                        <?php
                    }
                    else if($_GET['page'] === "announcement"){
                        ?>
                        <div class="config-box">
                            <div class="config-title">
                                ANNOUNCEMENTS
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                <table>
                                    <tr>
                                        <td style="width: 1350px;">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control config-announcement-input" placeholder="Write a message to all user" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-success" id="config-announcement">Submit</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="config-request-result"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="config-box">
                            <div class="config-content config-announcement">
                                <?php
                                    $query = "SELECT idx, message, date FROM announcement ORDER BY idx DESC";
                                    $result = $mysqli->query($query);
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        ?>
                                        <div class="config-announcement-box">
                                            <div class="config-announcement-result" id="<?php echo $row['idx']; ?>">
                                                <div class="config-announcement-date">
                                                    <?php echo $row['date']; ?>
                                                </div>
                                                <div class="config-announcement-content">
                                                    <?php echo $row['message']; ?>
                                                </div>
                                                <div class="config-announcement-result-btn">
                                                    Delete
                                                </div>
                                            </div>
                                            <div class="config-hr">
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        
                        <?php
                    }
                    else if($_GET['page'] === "challenge"){
                        ?>
                        <div class="config-box">
                            <div class="config-title">
                                Create new challenge
                                <div class="config-right">
                                    <div class="config-challenge-request-result"></div>
                                    <button type="button" class="btn btn-outline-success config-challenge-new" value="new">Create New Challenge</button>
                                </div>
                                <div class="config-hr"></div>
                            </div>
                            <div class="config-content" style="display: none">
                                <table class="config-content-challenge">
                                    <tr>
                                        <td style="width: 700px;">
                                            <h4>Title</h4>
                                            <input type="text" class="config-input config-challenge-input" name="config-challenge-new-title">
                                        </td>
                                        <td colspan="3">
                                            <h4>Flag</h4>
                                            <input type="text" class="config-input config-challenge-input" name="config-challenge-new-flag">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">
                                            <h4>Contents &nbsp;&nbsp;(You can use html code.)</h4>
                                            <textarea class="config-challenge-textarea" name="config-challenge-new-contents"></textarea>
                                        </td>
                                        <td>
                                            <h4>Points</h4>
                                            <input type="text" class="config-input" name="config-challenge-new-points">
                                        </td>
                                        <td>
                                            <h4>First Bonus</h4>
                                            <input type="text" class="config-input" name="config-challenge-new-bonus">
                                        </td>
                                        <td>
                                            <h4>Decrease</h4>
                                            <input type="text" class="config-input" name="config-challenge-new-decrease">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <h4>Category</h4>
                                            <select class="browser-default custom-select config-category" name="config-challenge-new-category">
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
                                        </td>
                                        <td>
                                            <h4>Level</h4>
                                            <select class="browser-default custom-select  config-challenge-new-level" name="config-challenge-new-level">
                                                <option value="easy">easy</option>
                                                <option value="normal">normal</option>
                                                <option value="hard">hard</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div><?php
                        
                        $challenge_query = "SELECT idx, title, contents, flag, points, bonus, decrease, attach, category, level, visible FROM challenge ORDER BY idx DESC";
                        $result = $mysqli->query($challenge_query);
                        $count = 0;
                        
                        while($row = mysqli_fetch_array($result)){
                            $count++;
                            ?>
                            <div class="config-box" id="challenge-<?php echo $row['idx']; ?>">
                                <div class="config-title">
                                    Challenge <?php echo $row['idx']; ?>
                                    <div class="config-right">
                                        <div class="config-challenge-request-result"></div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                           <input type="radio" id="customRadioInline<?php echo $count + 1; ?>" value="visible" name="customRadioInline<?php echo $count + 1; ?>" class="custom-control-input" <?php if($row['visible'] == 1){ echo "checked"; } ?> disabled>
                                           <label class="custom-control-label" for="customRadioInline<?php echo $count + 1; ?>">Visible</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                           <input type="radio" id="customRadioInline<?php echo $count + 100; ?>" value="hidden" name="customRadioInline<?php echo $count + 1; ?>" class="custom-control-input" <?php if($row['visible'] == 0){ echo "checked"; } ?> disabled>
                                           <label class="custom-control-label" for="customRadioInline<?php echo $count + 100; ?>">Hidden</label>
                                        </div>
                                        <button type="button" class="btn btn-outline-success" id="<?php echo $row['idx']; ?>" value="viewer">Edit challenge</button>
                                    </div>
                                </div>
                                <div class="config-hr"></div>
                                <div class="config-content">
                                    <table class="config-content-challenge" id="<?php echo "idx".$row['idx']; ?>">
                                        <tr>
                                            <td style="width: 700px;">
                                                <h4>Title</h4>
                                                <input type="text" value="<?php echo htmlspecialchars($row['title']); ?>" class="config-input config-challenge-input" name="challenge_title" id="challenge-title" readonly>
                                            </td>
                                            <td colspan="3">
                                                <h4>Flag</h4>
                                                <input type="text" value="<?php echo htmlspecialchars($row['flag']); ?>" class="config-input config-challenge-input" name="challenge_flag" id="challenge-flag" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">
                                                <h4>Contents &nbsp;&nbsp;(You can use html code.)</h4>
                                                <textarea class="config-challenge-textarea" name="challenge_contents" id="challenge-content" readonly><?php echo htmlspecialchars($row['contents']); ?></textarea>
                                            </td>
                                            <td>
                                                <h4>Points</h4>
                                                <input type="text" value="<?php echo $row['points']; ?>" class="config-input" name="challenge_points" id="challenge-points" readonly>
                                            </td>
                                            <td>
                                                <h4>First Bonus</h4>
                                                <input type="text" class="config-input" name="challenge_bonus" value="<?php echo $row['bonus']; ?>" id="challenge-bonus" readonly>
                                            </td>
                                            <td>
                                                <h4>Decrease</h4>
                                                <input type="text" class="config-input" name="challenge_decrease" value="<?php echo $row['decrease']; ?>" id="challenge-decrease" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <h4>Category</h4>
                                                <select class="browser-default custom-select config-category" disabled id="challenge-category">
                                                    <?php
                                                        $category_query = "SELECT * FROM category";
                                                        $result2 = $mysqli->query($category_query);
                                                        
                                                        while ($category = mysqli_fetch_array($result2)){
                                                            ?>
                                                            <option <?php if(strtolower($category['category_name']) == strtolower($row['category'])){ echo "selected"; } ?> value="<?php echo strtolower($category['category_name']); ?>"><?php echo strtolower($category['category_name']); ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <h4>Level</h4>
                                                <select class="browser-default custom-select  config-challenge-level" disabled>
                                                    <option value="easy" <?php if($row['level'] === "easy") { echo "selected"; } ?>>easy</option>
                                                    <option value="normal" <?php if($row['level'] === "normal") { echo "selected"; } ?>>normal</option>
                                                    <option value="hard" <?php if($row['level'] === "hard") { echo "selected"; } ?>>hard</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <!--<tr>-->
                                        <!--    <td colspan="4">-->
                                        <!--        <button type="button" class="btn btn-outline-info" disabled>Add link</button>-->
                                        <!--    </td>-->
                                        <!--</tr>-->
                                        <!--<tr>-->
                                        <!--    <td colspan="4">-->
                                        <!--        <h4>Hint List</h4>-->
                                        <!--        <div class="input-group mb-3">-->
                                        <!--            <input type="text" class="config-input config-challenge-hint" name="challenge_hint" id="challenge-hint" readonly> -->
                                        <!--            <div class="input-group-append">-->
                                        <!--                <button type="button" class="btn btn-outline-danger">Delete</button>-->
                                        <!--            </div>-->
                                        <!--        </div>-->
                                        <!--    </td>-->
                                        <!--</tr>-->
                                    </table>
                                </div>
                            </div><?php
                        }
                    }
                    else if($_GET['page'] === "user"){
                        ?>
                        <div class="config-box">
                            <div class="config-title">
                                All Users option
                                <div class="config-right">
                                    <div class="custom-control custom-radio custom-control-inline">
                                       <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                                       <label class="custom-control-label" for="customRadioInline1">Unlock All</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                       <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
                                       <label class="custom-control-label" for="customRadioInline2">Lock All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="config-hr"></div>
                        </div>
                        <?php 
                            $user_query = "SELECT userid, nickname, points, admin, visible FROM user";
                            $result = $mysqli->query($user_query);
                            $count = 0;
                            while($user_info = mysqli_fetch_array($result)){
                                $count++;
                                ?>
                                <div class="config-box">
                                    <div class="config-title">
                                        User <?php echo $count; ?>
                                        <div class="config-right">
                                            <!--<div class="custom-control custom-radio custom-control-inline">-->
                                            <!--   <input type="radio" id="customRadioInline3" name="customRadioInline3" class="custom-control-input">-->
                                            <!--   <label class="custom-control-label" for="customRadioInline3">Unlock</label>-->
                                            <!--</div>-->
                                            <!--<div class="custom-control custom-radio custom-control-inline">-->
                                            <!--   <input type="radio" id="customRadioInline0" name="customRadioInline3" class="custom-control-input">-->
                                            <!--   <label class="custom-control-label" for="customRadioInline0">Lock</label>-->
                                            <!--</div>-->
                                            <div class="config-challenge-request-result"></div>
                                            <button type="button" class="btn btn-outline-success" value="viewer">Edit User Info</button>
                                        </div>
                                    </div>
                                    <div class="config-hr"></div>
                                    <div class="config-content">
                                        <table>
                                            <tr>
                                                <td style="width: 500px;">
                                                    <h4>ID</h4>
                                                    <input type="text" class="config-input config-input-id" name="userid" id="user-id" value="<?php echo $user_info['userid']; ?>" disabled>
                                                </td>
                                                <td  style="width: 500px;">
                                                    <h4>Reset Password</h4>
                                                    <input type="password" class="config-input config-input-id" name="reset-password" id="user-reset-password" placeholder="Input new password." disabled>
                                                </td>
                                                <td class="user-auth">
                                                    <h4>Authority</h4>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                       <input type="radio" value="admin" id="customRadioInline<?php echo $count + 10; ?>" id="user-admin" name="customRadioInline<?php echo $count + 10; ?>" class="custom-control-input" <?php if($user_info['admin'] == 1) { echo "checked"; } ?> disabled>
                                                       <label class="custom-control-label" for="customRadioInline<?php echo $count + 10; ?>">Admin user</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                       <input type="radio" value="common" id="customRadioInline<?php echo $count + 1000; ?>"  id="user-common"  name="customRadioInline<?php echo $count + 10; ?>" class="custom-control-input" <?php if($user_info['admin'] == 0) { echo "checked"; } ?> disabled>
                                                       <label class="custom-control-label" for="customRadioInline<?php echo $count + 1000; ?>">Common user</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>Nickname</h4>
                                                    <input type="text" class="config-input config-input-id" name="nickname"  id="user-nickname" value="<?php echo $user_info['nickname']; ?>" disabled>
                                                </td>
                                                <td>
                                                    <h4>Confirm Password</h4>
                                                    <input type="password" class="config-input config-input-id" name="confirm-password" id="user-confirm-password" placeholder="Input new password again." disabled>
                                                </td>
                                                <td class="user-visible">
                                                    <h4>Display</h4>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                       <input type="radio" value="visible" id="customRadioInline<?php echo $count + 100; ?>" name="customRadioInline<?php echo $count + 100; ?>" class="custom-control-input" <?php if($user_info['visible'] == 1) { echo "checked"; } ?> disabled>
                                                       <label class="custom-control-label" for="customRadioInline<?php echo $count + 100; ?>">Visible</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                       <input type="radio" value="hidden" id="customRadioInline<?php echo $count + 10000; ?>" name="customRadioInline<?php echo $count + 100; ?>" class="custom-control-input" <?php if($user_info['visible'] == 0) { echo "checked"; } ?> disabled>
                                                       <label class="custom-control-label" for="customRadioInline<?php echo $count + 10000; ?>">Hidden</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>Points</h4>
                                                    <input type="text" class="config-input config-input-id" name="user_points" id="user-points"  value="<?php echo $user_info['points']; ?>" disabled>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                        
                        ?>
                        <?php
                    }
                    else if($_GET['page'] === "log"){
                        ?>
                        <div class="config-box">
                            <div class="config-title">
                                Event Logs
                                <div class="config-right">
                                    Search nickname: &nbsp;&nbsp;
                                    <input type="text" class="config-input config-log-input" placeholder="Input nickname">
                                    <!--<button type="button" class="btn btn-outline-danger config-delete-log">Delete logs</button>-->
                                </div>
                            </div>
                            <div class="config-hr"></div>
                        </div> 
                        <div class="config-box config-log">
                            <?php
                            
                            $logs_query = "SELECT * FROM logs ORDER BY idx DESC";
                            $logs = $mysqli->query($logs_query);
                            
                            while($row = mysqli_fetch_array($logs)){
                                ?>
                                <div class="config-announcement-box config-log-<?php echo $row['nickname']; ?>">
                                    <div class="config-announcement-result" id="<?php echo $row['idx']; ?>">
                                        <div class="config-announcement-date">
                                            <?php echo $row['date']; ?>
                                        </div>
                                        <?php
                                            if ($row['category'] === "success"){
                                                ?>
                                                <div class="config-log-success">&nbsp;&nbsp;</div>
                                                <?php
                                            }
                                            else if($row['category'] === "wrong"){
                                                ?>
                                                <div class="config-log-wrong">&nbsp;&nbsp;</div>
                                                <?php
                                            }
                                        ?>
                                        <div class="config-announcement-content">
                                            <div class="config-log-nickname">
                                                User: <font color="#d46313"><?php echo htmlspecialchars($row['nickname']); ?></font>
                                            </div>
                                            <div class="config-log-title">
                                                Title: <font color="#d46313"><?php echo htmlspecialchars($row['title']); ?></font>
                                            </div>
                                            <div class="config-log-submit">
                                                Submit flag: <font color="#d46313"><?php echo htmlspecialchars($row['submit']); ?></font>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="config-hr">
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        </div>
                        <div class="config-box config-log-result" style="display: none">
                        </div>
                        <?php
                    }
                    else if($_GET['page'] == "category"){
                        ?>
                            <div class="config-box">
                            <div class="config-title">
                                CATEGORIES
                            </div>
                            <div class="config-hr"></div>
                            <div class="config-content">
                                <table>
                                    <tr>
                                        <td style="width: 1350px;">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control config-announcement-input" placeholder="Create new category" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-success" id="config-category">Submit</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="config-request-result"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="config-box">
                            <div class="config-content config-announcement">
                                <?php
                                    $query = "SELECT * FROM category";
                                    $result = $mysqli->query($query);
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        ?>
                                        <div class="config-announcement-box">
                                            <div class="config-announcement-result <?php echo "config-category-".$row['category_name']; ?>">
                                                <div class="config-announcement-content">
                                                    <?php echo $row['category_name']; ?>
                                                </div>
                                                <div class="config-category-result-btn">
                                                    Delete
                                                </div>
                                            </div>
                                            <div class="config-hr">
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                            <?php
                    }
                    else{
                        header("Location: /admin/?page=configuration");
                    }
                ?>
                
            </div>
        </div>
    </div>
    <div id="footer">
        Casper Capture The Flag<br>
        Designed by <a href="https://profile.lactea.kr" target="_blank">universe</a>
    </div>
    <input type="hidden" class="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <script type="text/javascript" src="/js/admin/requestConfig.js"></script>
    <script type="text/javascript" src="/js/admin/etc.js"></script>
</body>
</html>