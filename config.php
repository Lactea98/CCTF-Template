<?php
    session_start();
    
    if($_SESSION['isLogin']){
        $option = $_POST['option'];
        if(isset($option)){
            include "./db.php";
            
            if($option === "getAnnouncement"){
                $query = "SELECT idx, category, message, date_format(date, '%m-%d %H:%i') as date FROM announcement ORDER BY idx DESC";
                $result = $mysqli->query($query);
                
                $resultAnnounce = array();
                while($row = mysqli_fetch_assoc($result)){
                    $resultAnnounce[] = $row;
                }
                // echo json_encode($resultAnnounce);
                $response = array("result" => "success", "message" => $resultAnnounce);
            }
            else if($option === "getChatting"){
                
            }
            else{
                $response = array("result" => "refuse", "message" => "Wrong parameter.");
            }
        }
        else{
            $response = array("result" => "refuse", "message" => "Need parameter.");
        }
    }
    else{
        $response = array("result" => "refuse", "message" => "Need login.");
    }
    
    header('Content-Type: application/json');
    echo json_encode($response, true);
?>