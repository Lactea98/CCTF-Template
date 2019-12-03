<?php
    /*
        [*] Introduce
                -> Made by universe
                -> https://profile.lactea.kr
        
        [*] Description
                -> File name: checkFlag.php
                -> This php file checks if user input value is correct flag or not.
        
        [*] Since 2019.10.31 ~
        
    */
    
    session_start();
    
    include "./db.php";
    
    $query = "SELECT begin_timer <= now() as now, end_timer <= now() as end FROM config";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_array($result);
    
    // CCTF가 시작 했는지 확인
    if($row['now'] != 1){   // 1이면 시작 됨
        $response = array("result" => "CCTF is not started.");
        
        header('Content-Type: application/json');
        echo json_encode($response, true);
        exit();
    }
    if($row['end'] == 1){   // 1이면 끝남
        $response = array("result" => "CCTF is ended.");
        
        header('Content-Type: application/json');
        echo json_encode($response, true);
        exit();
    }
    
    // Login check
    if($_SESSION['isLogin']){
        if(isset($_POST['idx'], $_POST['flag'], $_POST['csrf_token'])){
            if($_POST['csrf_token'] === $_SESSION['csrf_token']){
                $p_idx = $_POST['idx'];
                $p_flag = $_POST['flag'];
                $nickname = $_SESSION['nickname'];
                
                // Search idx
                if($result = $mysqli->prepare("SELECT flag, points, first_solver, solved, solver_list, bonus, decrease FROM challenge WHERE idx=? AND flag=?")){
                    $result->bind_param('is', $p_idx, $p_flag);
                    $result->execute();
                    $result->store_result();

                    // Correct flag
                    if($result->num_rows > 0){
                        $result->bind_result($flag, $c_points, $c_first_solver, $c_solved, $c_solver_list, $c_bonus, $c_decrease);
                        $result->fetch();
                        
                        // Check if this challenge already solved 
                        if($userinfo = $mysqli->prepare("SELECT points, solved_challenge FROM user WHERE nickname=?")){
                            $userinfo->bind_param("s", $nickname);
                            $userinfo->execute();
                            $userinfo->store_result();
                            $userinfo->bind_result($u_points, $u_solved_challenge);
                            $userinfo->fetch();
                            
                            $u_challenge_list = explode(",", $u_solved_challenge);
                            for($i=0; $i<count($u_challenge_list); $i++){
                                if($p_idx == $u_challenge_list[$i]){
                                    $response = array("result" => "Already solved");
                                    
                                    header('Content-Type: application/json');
                                    echo json_encode($response, true);
                                    exit();
                                }
                            }


                            /*  
                                [*] Update challenge's first_solver and solved
                            */
                            // Not exist first solver
                            $result_bonus = 0;
                            if($c_first_solver === ""){
                                // Register first solver nickname
                                if($update = $mysqli->prepare("UPDATE challenge SET first_solver=? WHERE idx=?")){
                                    $update->bind_param("si", $nickname, $p_idx);
                                    $update->execute();
                                    $update->close();
                                    
                                    // 맨 처음으로 푼 유저에게 보너스 점수 주기
                                    $result_bonus = $c_bonus;
                                }
                            }
                            // Count solved challenge
                            $c_solved = $c_solved + 1;
                            $result_solver_list = $c_solver_list . $nickname . ",";
                            if($update = $mysqli->prepare("UPDATE challenge SET solved=?, solver_list=? WHERE idx=?")){
                                $update->bind_param("isi", $c_solved, $result_solver_list, $p_idx); 
                                $update->execute();
                                $update->close();
                            }
                            
                            
                            /*  
                                [*] Update user's points, last_time and solved_challenge
                            */
                            // Update user's points
                            $result_points = $u_points + $c_points + $result_bonus;
                            $solved_challenge = $u_solved_challenge . $p_idx . ",";
                            if($update = $mysqli->prepare("UPDATE user SET points=?, last_time=now(), solved_challenge=? WHERE nickname=?")){
                                $update->bind_param("iss", $result_points, $solved_challenge, $nickname);
                                $update->execute();
                                $update->close();
                            }
                            $userinfo->close();
                            
                            
                            /*
                                문제를 풀었을 경우 그 문제 점수 decrease
                            */
                            $min_points = 10;
                            if($c_points - $c_decrease < $min_points){
                                $c_result_points = 10;
                            }
                            else{
                                $c_result_points = $c_points - $c_decrease;
                            }
                            if($challenge_decrease = $mysqli->prepare("UPDATE challenge SET points=? WHERE idx=?")){
                                $challenge_decrease->bind_param("ii",$c_result_points, $p_idx);
                                $isErr = $challenge_decrease->execute();
                                
                                if($isErr === false){
                                    // $challenge_decrease->close();
                                    $response = array("result" => "DB error.");
                                    
                                    header('Content-Type: application/json');
                                    echo json_encode($response, true);
                                    exit();
                                }
                                // $challenge_decrease->close();
                            }
                            
                            //////////////////////////////////////////
                            // log 테이블에 "success" 기록
                            if($getTitle = $mysqli->prepare("SELECT title FROM challenge WHERE idx=?"));{
                                $getTitle->bind_param("i", $p_idx);
                                $getTitle->execute();
                                $getTitle->bind_result($title);
                                $getTitle->fetch();
                            }
                            
                            $log_category = "success";
                            $log_title = $title;
                            $log_flag = $p_flag;
                            $log_nickname = $_SESSION['nickname'];
                            // $log_message = "User: <font color=d46313>" . $log_nickname . "</font><br>Title: <font color=#d46313>" . htmlspecialchars($title) . "</font><br>Submit flag: <font color=#d46313>" . htmlspecialchars($p_flag) . "</font>";
                            
                            $getTitle->close();
                            
                            if($logs_query = $mysqli->prepare("INSERT INTO logs (category, nickname, submit, title, date) VALUES (?,?,?,?,now())")){
                                $logs_query->bind_param("ssss", $log_category, $log_nickname, $log_flag, $log_title);
                                $logs_query->execute();
                                $logs_query->close();
                                
                                // announcement 테이블에 유저가 문제를 풀었다고 알리기
                                if($announce = $mysqli->prepare("INSERT INTO announcement (category, message, date) VALUES ('captured', ?, now())")){
                                    $message =  htmlspecialchars($log_nickname . " || " . $log_title);
                                    $announce->bind_param("s", $message);
                                    $announce->execute();
                                    $announce->close();
                                    
                                    $response = array("result" => "success", "solved" => $c_solved, "solver_list" => $result_solver_list);
                                }
                                else{
                                    $response = array("result" => $mysqli->error);
                                }
                            }
                            else{
                                $response = array("result" => "DB error.");
                            }
                            
                            header('Content-Type: application/json');
                            echo json_encode($response, true);
                        }
                    }
                    // Wrong flag
                    else{
                        // log 테이블에 "wrong" 기록
                        if($getTitle = $mysqli->prepare("SELECT title FROM challenge WHERE idx=?"));{
                            $getTitle->bind_param("i", $p_idx);
                            $getTitle->execute();
                            $getTitle->bind_result($title);
                            $getTitle->fetch();
                        }
                        
                        $log_category = "wrong";
                        $log_title = $title;
                        $log_flag = $p_flag;
                        $log_nickname = $_SESSION['nickname'];
                        // $log_message = $_SESSION['nickname'] . ", " . $title . ", " . $p_flag;
                        // $log_message = "User: <font color=d46313>" . $log_nickname . "</font><br>Title: <font color=#d46313>" . htmlspecialchars($title) . "</font><br>Submit flag: <font color=#d46313>" . htmlspecialchars($p_flag) . "</font>";
                        
                        $getTitle->close();
                        
                       if($logs_query = $mysqli->prepare("INSERT INTO logs (category, nickname, submit, title, date) VALUES (?,?,?,?,now())")){
                            $logs_query->bind_param("ssss", $log_category, $log_nickname, $log_flag, $log_title);
                            $logs_query->execute();
                        }
                        
                        $response = array("result" => "wrong_flag");
                        
                        header('Content-Type: application/json');
                        echo json_encode($response, true);
                        
                        $logs_query->close();
                    }
                }
            }
            else{
                // Wrong csrf_token
                $response = array("result" => "wrong_csrf_token");
                            
                header('Content-Type: application/json');
                echo json_encode($response, true);
            }
        }
        else{
            // Need idx, flag, csrf_token
            $response = array("result" => "error");
                            
            header('Content-Type: application/json');
            echo json_encode($response, true);
        }
    }
    else{
        // Need login
        $response = array("result" => "error");
                            
        header('Content-Type: application/json');
        echo json_encode($response, true);
    }
    // update challenge set solved=0, first_solver='', solver_list='';
    // update user set points=0, last_time='', solved_challenge='';
?>