<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the values from the POST data
    $response = isset($_POST["response"]) ? $_POST["response"] : null;
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : null;
    $roleId = isset($_POST["roleId"]) ? $_POST["roleId"] : null;
    $teamId = isset($_POST["teamId"]) ? $_POST["teamId"] : null;

    // remove member
    include("dbconn.php");
    $rolemember = "DELETE FROM `role_teams` WHERE `role_id` = '$roleId' AND `team_id` = '$teamId' AND `user_id` = '$userId'";
    if($roleId==3){
        $remove_manager = "UPDATE `teams` SET `team_manager`='' WHERE `id` = '$teamId'";
        if(mysqli_query($conn,$rolemember)){
            if(mysqli_query($conn,$remove_manager)){
                echo "member remove";
            }
        }
    }else{
        $usermember = "UPDATE `users` SET `role_id`='5' WHERE `id` = '$userId'";
        if(mysqli_query($conn,$rolemember) && mysqli_query($conn,$usermember) ){
            echo "member remove";
        }
    }
    // You can now use these variables in your PHP code
    // For example, you might want to save them to a file, update a database, or perform other actions

    // Sample: Log the values to a file
    // $logMessage = "Response: $response, User ID: $userId, roleId: $roleId, teamId: $teamId";
    // file_put_contents("log.txt", $logMessage . PHP_EOL, FILE_APPEND);

    // Send a response back to the JavaScript (optional)
  //  echo "Received values: Response - $response, User ID - $userId, roleId - $roleId, teamId - $teamId";
} else {
    // Handle invalid requests
    http_response_code(400);
    echo "Invalid request";
}
?>
