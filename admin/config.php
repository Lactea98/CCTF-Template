<?php 
    session_start();
    
    // Create sure PHP CSRF
    if(empty($_SESSION['csrf_token']) || $_SESSION['csrf_token_lifeline'] < time()){
        $_SESSION['csrf_token_lifeline'] = time() + 3600;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    // Check correct parameters
    if(!isset($_POST['csrf_token'], $_POST['option'])){
        $response = array("message" => "Wrong request", "category" => "all", "result" => "error");
    }
    
    // Check admin
    if($_SESSION['admin'] !== 1){
        $response = array("message" => "Please, login.", "category" => "all", "result" => "error");
    }
    
    // Check csrf token
    if($_SESSION['csrf_token'] !== $_POST['csrf_token']){
        $response = array("message" => "Wrong csrf token. Please refresh your page.", "category" => "all", "result" => "error");
    }
    
    if(isset($response)){
        header("Content-Type: application/json");
        echo json_encode($response, true);
        exit();
    }
    
    
    // Start
    include "../db.php";
    
    $option = explode("-",$_POST['option']);
    
    // Configuration part
    if($option[0] === "configuration"){
        $result_column = $option[1] === "login" ? "login" : ($option[1] === "register" ? "registration" : false);
        $result_value = $option[2] === "on" ? 1 : ($option[2] === "off" ? 0 : false);
        
        // Check bad value
        if($result_column === false || $result_value === false){
            $response = array("message" => "Wrong value.", "category" => "configuration", "result" => "error");
        }
        
        else{
            $config_query = "UPDATE config SET ".$result_column." = ".$result_value;
            $result = $mysqli->query($config_query);
            
            $response = array("message" => "Successfully chaneged.", "category" => $result_column, "result" => "success");
        }
    }
    
    // Timer part
    else if($option[0] === "timer"){
        if(!isset($_POST['begin_datetime'], $_POST['end_datetime'])){
            $response = array("message" => "Wrong value.", "category" => "timer", "result" => "error");
        }
        else{
            $begin_datetime = $_POST['begin_datetime'];
            $end_datetime = $_POST['end_datetime'];
            
            if($result = $mysqli->prepare("UPDATE config SET begin_timer = ?, end_timer = ?")){
                $result->bind_param("ss", $begin_datetime, $end_datetime);
                $isErr = $result->execute();
                
                if($isErr === false){
                    $response = array("message" => "DB error because of wrong value.", "category" => "timer", "result" => "error");
                }
                else{
                    $response = array("message" => "Successfully changed.", "category" => "timer", "result" => "success");
                }
            }
            else{
                $response = array("message" => "DB error.", "category" => "timer", "result" => "error");
            }
            $result->close();
        }
    }
    
    // Announcement part
    else if($option[0] === 'announcement'){
        $mode = $option[1] === "add" ? "add" : ($option[1] === "delete" ? "delete" : false);
        
        if($mode === false || !isset($_POST['value'])){
            $response = array("message" => "Wrong value.", "category" => "announcement", "result" => "error");
        }
        else{
            if($mode === "add"){
                $input = $_POST['value'];
                $current_time = date("Y-m-d H:i:s");
                
                if($result = $mysqli->prepare("INSERT INTO announcement (category, message, date) VALUES ('admin', ?, ?)")){
                    $result->bind_param("ss", $input, $current_time);
                    $isErr = $result->execute();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error because of wrong value.", "category" => "announcement", "result" => "error");
                    }
                    else{
                        $response = array("message" => "Successfully registered.", "category" => "announcement", "result" => "success", "value" => $current_time);
                    }
                }
                else{
                    $response = array("message" => "DB error.", "category" => "announcement", "result" => "error");
                }
                $result->close();
            }
            else if($mode === "delete"){
                $idx = $_POST['value'];
                
                if($result = $mysqli->prepare("DELETE FROM announcement WHERE idx = ?")){
                    $result->bind_param("s", $idx);
                    $isErr = $result->execute();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error because of wrong value.", "category" => "announcement", "result" => "error");
                    }
                    else{
                        $response = array("message" => "Successfully deleted.", "category" => "announcement", "result" => "success");
                    }
                }
                else{
                    $response = array("message" => "DB error.", "category" => "announcement", "result" => "error");
                }
            }
        }
    }
    else if($option[0] === "challenge"){
        //////////////////////////
        // 문제를 만들때 
        if(isset($option[1]) && $option[1] === "create"){
            $title = $_POST['title'];
            $content = $_POST['contents'];
            $flag = $_POST['flag'];
            $points = $_POST['points'];
            $bonus = $_POST['bonus'];
            $decrease = $_POST['decrease'];
            $category = $_POST['category'];
            $level = $_POST['level'];
            $isVisible = 0;
            
            
            if(strlen($title) * strlen($content) * strlen($flag) * strlen($points) * strlen($bonus) * strlen($decrease) * strlen($category) * strlen($level) == 0){
                // echo (strlen($title) . " " . strlen($content) . " " . strlen($flag) . " " . strlen($points) . " " . strlen($bonus) . " " . strlen($decrease) . " " . strlen($category));
                $response = array("message" => "You need to full input box all.", "category" => "challenge", "result" => "error");
            }
            else if($insert = $mysqli->prepare("INSERT INTO challenge (title, contents, flag, points, bonus, decrease, visible, category, solved, level, first_solver, solver_list) VALUES (?,?,?,?,?,?,?,?, 0, ?, '', '')")){
                $insert->bind_param("sssiiiiss", $title, $content, $flag, $points, $bonus, $decrease, $isVisible, $category, $level);
                $isErr = $insert->execute();
                $insert->close();
                if($isErr === false){
                    $response = array("message" => "DB error.", "category" => "challenge", "result" => "error");
                }
                else{
                    $response = array("message" => "Successfully create new challenge.", "category" => "challenge", "result" => "success");
                }
            }
            else{
                $response = array("message" => "DB error.", "category" => "challenge", "result" => "error");
            }
        } 
        ///////////////////////
        
        
        ///////////////////////
        // 문제를 수정할 때
        else if($option[1] === "update"){
            $idx = $_POST['idx'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $flag = $_POST['flag'];
            $points = $_POST['points'];
            $bonus = $_POST['bonus'];
            $decrease = $_POST['decrease'];
            $category = $_POST['category'];
            $isVisible = $_POST['visible'];
            $level = $_POST['level'];
                
            if(strlen($idx) * strlen($title) * strlen($content) * strlen($flag) * strlen($points) * strlen($bonus) * strlen($decrease) * strlen($category) * strlen($isVisible) * strlen($level) == 0){
                $response = array("message" => "You need to full input box all.", "category" => "challenge", "result" => "error");
            }
            else if($result = $mysqli->prepare("UPDATE challenge set title=?, contents=?, flag=?, points=?, bonus=?, decrease=?, visible=?, category=?, level=? WHERE idx=?")){
                $result->bind_param("sssiiiissi", $title, $content, $flag, $points, $bonus, $decrease, $isVisible, $category, $level, $idx);
                $isErr = $result->execute();
    
                if($isErr === false){
                    $result->close();
                    $response = array("message" => "Not exist idx.", "category" => "challenge", "result" => "error");
                }
                else{
                    $result->close();
                    
                    // announcement 테이블에 challenge가 update 되었다고 알리기
                    if($isVisible == 1){
                        if($insert2 = $mysqli->prepare("INSERT INTO announcement (category, message, date) VALUES ('challenge', ?, now())")){
                            $message = $title . " challenge is updated.";
                            $insert2->bind_param("s", $message);
                            $isErr = $insert2->execute();
                            
                            if($isErr === false){
                                $response = array("message" => "Successfully updated, but Error cause in announcement table during inserting.", "category" => "challenge", "result" => "success");
                            }
                            else{
                                $response = array("message" => "Successfully updated and announcement table is inserted.", "category" => "challenge", "result" => "success");
                            }
                        }
                    }
                    else{
                        $response = array("message" => "Successfully updated.", "category" => "challenge", "result" => "success");
                    }
                }
            }
            else{
                $response = array("message" => "DB error.", "category" => "challenge", "result" => "error");
            }
        }
        /////////////////
        
        
        /////////////////////////
        // 문제를 삭제 할때 
        else if($option[1] === "delete"){
            $idx = $_POST['idx'];
            
            if(isset($idx)){
                if($delete = $mysqli->prepare("DELETE FROM challenge WHERE idx=?")){
                    $delete->bind_param("i", $idx);
                    $isErr = $delete->execute();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error during using idx.", "category" => "challenge", "result" => "error");
                    }
                    else{
                        $response = array("message" => "Successfully deleted.", "category" => "challenge", "result" => "success");
                    }
                }
                else{
                    $response = array("message" => "DB error during deleting.", "category" => "challenge", "result" => "error");
                }
            }
            else{
                $response = array("message" => "Wrong parameter.", "category" => "challenge", "result" => "error");
            }
        }
        ////////////////////////
        
        else{
            $response = array("message" => "Wrong parameter.", "category" => "challenge", "result" => "error");
        }
    }
    else if($option[0] === "user"){
        $userid = $_POST['userid'];
        $points = $_POST['points'];
        $pw1 = $_POST['pw1'];
        $pw2 = $_POST['pw2'];
        $visible = $_POST['visible'];
        $auth = $_POST['auth'];
    
        if(isset($userid, $points, $visible, $auth)){
            // 패스워드를 변경 할 경우
            if(isset($pw1, $pw2) && strlen($pw1) != 0 && strlen($pw2) != 0){
                if(strlen($pw1) == strlen($pw2) && 8<=strlen($pw1) && strlen($pw1)<=30){
                    $password = password_hash($pw1, PASSWORD_DEFAULT);
                    $result = $mysqli->prepare("UPDATE user SET userpw=?, points=?, admin=?, visible=? WHERE userid=?");
                    $result->bind_param("siiis", $password, $points, $auth, $visible, $userid);
                    $isErr = $result->execute();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error.", "category" => "user", "result" => "error");
                    }
                    else{
                        $response = array("message" => "Successfully updated.", "category" => "user", "result" => "success");
                    }
                }
                else{
                    $response = array("message" => "Password length is different.", "category" => "user", "result" => "error");
                }
            }
            // 패스워드를 변경하지 않는 경우
            else{
                if($result = $mysqli->prepare("UPDATE user SET points=?, admin=?, visible=? WHERE userid=?")){
                    $result->bind_param("iiis", $points, $auth, $visible, $userid);
                    $isErr = $result->execute();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error.", "category" => "user", "result" => "error");
                    }
                    else{
                        $response = array("message" => "Successfully updated.", "category" => "user", "result" => "success");
                    }
                }
            }
        }
        else{
            $response = array("message" => "Bad request.", "category" => "user", "result" => "error");
        }
    }
    else if($option[0] === "reset"){
        /*
            CCTF reset config list
            
            [*] user 테이블에서 초기화 컬럼 목록
                --> points, last_time, solved_challenge, history
            [*] announcement 테이블에서 초기화 컬럼 목록
                --> idx, message, date time
            [*] challenge 테이블에서 초기화 컬럼 목록
                --> solved, first_solver, solver_list
            [*] config 테이블에서 초기화 컬럼 목록
                --> start time, end time
            [*] logs 테이블에서 모든 컬럼 목록
        */
        
        // user 테이블 초기화
        $user_table = "UPDATE user SET points = 0, last_time = NULL, solved_challenge='', history=''";
        $mysqli->query($user_table);
        
        // announcement 테이블 초기화
        $announ_table = "DELETE FROM announcement";
        $mysqli->query($announ_table);
        
        // challenge 테이블 초기화
        $points = "SELECT idx, points, decrease, solved FROM challenge ORDER BY idx";
        $chall_info = $mysqli->query($points);
        while($row = mysqli_fetch_array($chall_info)){
            $init_points = $row["points"] + $row["decrease"] * $row["solved"];
            
            $chall_table = "UPDATE challenge SET points = ".$init_points.", solved = 0, first_solver = '', solver_list = '' WHERE idx = ".$row['idx'];
            $mysqli->query($chall_table);
        }
        
        // config 테이블 초기화
        $config_table = "UPDATE config SET begin_timer = NULL, end_timer = NULL";
        $mysqli->query($config_table);
        
        // logs 테이블 초기화
        $log_table = "DELETE FROM logs";
        $mysqli->query($log_table);
        
        $response = array("message" => "Successfully reset CCTF.", "category" => "configuration", "result" => "success");
    }
    else if($option[0] === "category"){
        if($option[1] === "add"){
            $value = strtolower($_POST['value']);
            
            if($search = $mysqli->prepare("SELECT category_name FROM category WHERE category_name = ?")){
                $search->bind_param("s", $value);
                $search->execute();
                $search->store_result();
                
                if($search->num_rows > 0){
                    $response = array("message" => "Category is existed.", "category" => "category", "result" => "error");
                    $search->close();
                }
                else{
                    $search->close();
                    
                    if($insert = $mysqli->prepare("INSERT INTO category (category_name) VALUES (?)")){
                        $insert->bind_param("s", $value);
                        $isErr = $insert->execute();
                        
                        if($isErr === false){
                            $response = array("message" => "DB error.", "category" => "category", "result" => "error");
                            $insert->close();
                        }
                        else{
                            $response = array("message" => "Successfully registered.", "category" => "category", "result" => "success");
                        }
                    }
                }
            }
        }
        else if($option[1] === "delete"){
            $value = $_POST['category_name'];
            
            if(isset($value)){
                if($find = $mysqli->prepare("SELECT category_name FROM category WHERE category_name = ?")){
                    $find->bind_param("s", $value);
                    $isErr = $find->execute();
                    $find->store_result();
                    
                    if($isErr === false){
                        $response = array("message" => "DB error.", "category" => "category", "result" => "error");
                    }
                    else{
                        if($find->num_rows > 0){
                            $find->close();
                            
                            if($delete = $mysqli->prepare("DELETE FROM category WHERE category_name = ?")){
                                $delete->bind_param("s", $value);
                                $isErr = $delete->execute();
                                
                                if($isErr === false){
                                    $response = array("message" => "DB error.", "category" => "category", "result" => "error");
                                }
                                else{
                                    $response = array("message" => "Successfully deleted.", "category" => "category", "result" => "success");
                                }
                                $delete->close();
                            }
                            else{
                                $response = array("message" => "DB error.", "category" => "category", "result" => "error");
                            }
                        }
                        else{
                            $response = array("message" => "Category is not existed.", "category" => "category", "result" => "error");
                        }
                    }
                }
                else{
                    $response = array("message" => "DB error.", "category" => "category", "result" => "error");
                }
            }
            else{
                $response = array("message" => "Need category_name.", "category" => "category", "result" => "error");
            }
        }
        else{
            $response = array("message" => "Bad request", "category" => "category", "result" => "error");
        }
    }
    
    else{
        $response = array("message" => "Bad request", "category" => "all", "result" => "error");
    }
    
    header("Content-Type: application/json");
    echo json_encode($response);
?>