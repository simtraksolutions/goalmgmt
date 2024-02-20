<?php
include("DB/dbconn.php");

session_start(); // Start the session to access session variables
$username = $_SESSION['user_id'];

// Fetch team goal
$teamID = $_SESSION["team_id"];
$sql1 = "SELECT * FROM `teams` WHERE `id` = '$teamID'";
$teamname_result = mysqli_query($conn, $sql1);
$teamname_obj = mysqli_fetch_object($teamname_result);
$teamname = $teamname_obj->team_name;

$goal_parameter = "SELECT * FROM `goal_parameter` WHERE team_id ='0' OR team_id = '$teamID' ORDER BY `goal_parameter`.`team_id` ASC ";
$parameter_result = mysqli_query($conn, $goal_parameter);

$parameters = array();
$goalparameters = array();
$array = array();
$i = 0;

$year = date('Y');
$month = date('n');
$setgoaldate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-00';
    

while ($para = mysqli_fetch_object($parameter_result)) {
    $parameters[] = $para;
    $array[$i] = $para->parameter;
    $i++;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_manager_id = $_POST["team_manager_id"];
    $team_manager_name = $_POST["team_manager_name"];
    $setgoalcheck = "SELECT * FROM `$teamname` WHERE `goalset` = '1'  AND `DATE` = '$setgoaldate'";
    $setgoalcheck = mysqli_query($conn,$setgoalcheck);
    $setgoalcheck = mysqli_num_rows($setgoalcheck);
    if($setgoalcheck >=1){
        $updategoal = "UPDATE `$teamname` SET `Member ID` = '$team_manager_id', `Member Name`= '$team_manager_name' WHERE `goalset` = '1' AND `DATE` = '$setgoaldate'";
    }else{
        $updategoal = "INSERT `$teamname` (`Member ID`, `Member Name`,`goalset`,`Date`) VALUE('$team_manager_id','$team_manager_name','1','$setgoaldate')";
    }
    if (mysqli_query($conn, $updategoal)) {
        $i = 0;
        foreach ($array as $value) {
            if ($value == 'Member Name' || $value == 'Date') {
                continue;
            }
            if(isset($_POST[$i])){
                $parameter_value = $_POST[$i];
            }else{
                $parameter_value = 0;
            }
            if($i==0){
                $team_target = "UPDATE `teams` SET `Target` = '$parameter_value' WHERE `id` = '$teamID'";
                $team_target = mysqli_query($conn,$team_target);
            }
            $updategoal = "UPDATE `$teamname` SET `$value`= '$parameter_value' WHERE `goalset` = '1' AND `DATE` = '$setgoaldate'";
            if (!mysqli_query($conn, $updategoal)) {
                echo $conn->error;
            }
            $i++;
        }
        echo "ok"; // Send response to JavaScript
    } else {
        echo "error"; // Send response to JavaScript
    }
}
?>
