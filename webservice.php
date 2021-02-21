<?php
//Austin Klevgaard
//Submission Code : 1201_2500_A06
require_once "./inc/db.php";
require_once 'functions.php';

// Set to always get fresh page processing, no caches supplied
header("Cache-Control: no-cache, must re-validate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

//globla definitions
$globalData = array();
$data_status = "webservice.php: No matches";

if (!isset($_SESSION['username']))
{
    Done();
}

function Done()
{
    global $globalData, $data_status, $mysqli, $mysqli_status;

    $outputData = array();
    $outputData['data'] = $globalData;
    $outputData['status'] = $data_status;

    echo json_encode($outputData);
    die();
}
//retrieves the user data from our CPanel database
function QueryUsers()
{
    global $globalData, $data_status, $mysqli, $mysqli_status;

    $query = "SELECT * FROM `users`";   //creats the querystring to iterrogate the database with
    
    //query the database and record the results
    if ($result = mysqliQuery($query))
    {
        $numRows = $result->num_rows;
        //copy the returned data into an array
        while ($row = $result->fetch_assoc())
        {
            $outputData[] = $row;
        }
        $status = "Retrieved : {$numRows} user records";
    }
    else
    {
        $status = "QueryUsers Error : {$query} generated {$mysqli_status}";
    }
    $globalData['jsonData'] = $outputData;
    $data_status = $status;
}

function AddUser()
{
    global $globalData, $data_status, $mysqli, $mysqli_status;

    //sanitize the user input
    $newUser = $mysqli->real_escape_string(strip_tags($_POST['user']));
    $newPass = $mysqli->real_escape_string(strip_tags($_POST['pass']));
    //create a new password hash
    $newHashPass = password_hash($newPass, PASSWORD_DEFAULT);
    //create a query for the database to insert the user info
    $insertQuery = "INSERT INTO `users`(`username`, `password`) VALUES ('{$newUser}', '{$newHashPass}')";
    $result =  mysqliNonQuery($insertQuery);
    //update the status
    $data_status = "User added successfully. {$result} record(s) altered.";

}

function DeleteUser($deleteUserID)
{
    global $globalData, $data_status, $mysqli, $mysqli_status;

    $deleteQuery = "DELETE FROM `users` WHERE `userID` = {$deleteUserID}";
    error_log($deleteQuery);
    $result = mysqliNonQuery($deleteQuery);
    //if query is successful
    if ($result > 0)
    {
        $data_status = "User delete successful. {$result} record(s) altered";
    }
    else if ($result == 0)
    {
        $data_status = "Error: No user record was deleted";
    }
    //fall back if there is an error in the delete user non-query
    else
    {
        $data_status = $mysqli_status;
    }
}

//Processing block GET_USERS: checks for a user action in the GET superglobal. If an action exists, and the action
//is a 'GET' request then query our database for user data, then process and return as a json
if( isset( $_GET['action']) && $_GET['action'] == 'GET')
{
    QueryUsers();
}

//Processing block: [action] - Add a user
if (isset($_POST['action']) && $_POST['action'] == 'AddUser')
{
    //check to ensure that the user is trying to add a unique username
    //sanitize username string
    $testUsername = $mysqli->real_escape_string(strip_tags($_POST['user']));
    //send a NonQuery to the database to check for like usernames to test
    $query = "SELECT * FROM `users` WHERE username like '%{$testUsername}%' ";
    $result = mysqliNonQuery($query);
    //if the result is 0 then the username is allowed
    if ($result == 0)
    {
        AddUser();
    }
    //if the username is not zero then it has already been taken 
    else if ($result > 0)
    {
        $data_status = "Username unavailable, try another.";
    }
    //or there was an error
    else
    {
        $data_status = "Error adding user to database";
    }  
}

//Processing block: [action] - Delete a User, but first queries the database to check that the operation is possible
if (isset($_POST['action']) && $_POST['action'] == 'DeleteUser')
{
        $testUserID = $mysqli->real_escape_string(strip_tags($_POST['userID']));

        //sentinel check - currently logged in user isn't allowed to delete themselves from the database while logged in
        if ($_SESSION['userID'] == $testUserID)
        {
            $data_status = "Error: User profile cannot be deleted while logged in";
            Done();
        }

        //send a NonQuery to the database to check for like usernames to test
        $query = "SELECT * FROM `users` WHERE userID like '%{$testUserID}%' ";
        $result = mysqliNonQuery($query);
        //if the result is 0 then the username is allowed
        if ($result == 1)
        {
            DeleteUser($testUserID);
        }
        else if ($result > 1)
        {
            $data_status = "Error: Too many matching userIDs";
        }
        //if the username is not zero then it has already been taken or there was an error
        else
        {
            $data_status = "Error: userID does not exist in the database";
        }  
}

//Fall through at the end of processing blocks
error_log("made to end of webservice call - fall through");

Done();
//die();
?>