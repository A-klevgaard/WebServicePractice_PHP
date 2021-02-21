<?php 
require_once "./inc/db.php";
//Austin Klevgaard
//Submission Code : 1201_2500_A06

$userTable = array();
$userTable['admin'] = password_hash('god',PASSWORD_DEFAULT);
$userTable['germf'] = password_hash('new123', PASSWORD_DEFAULT);

$ajaxData = array();
$result;

function Validate($userArray)
{
    //global $userTable;
    global $mysqli, $mysqli_status, $ajaxData, $result;

    //sanitize the user input
    $testUser = $mysqli->real_escape_string(strip_tags($userArray['username']));
    $testPass = $mysqli->real_escape_string(strip_tags($userArray['password']));

    $query = "SELECT * FROM `users` WHERE username like '%{$testUser}%' ";
    $result = mysqliQuery($query);
    error_log($result->num_rows);
    //if the result of the query is 1, then the query returned a valid unique username, so the password can be tested
    if ($result->num_rows == 1)
    {
        //if successful then check the password
        //$numRows = $result->num_rows;
        $row = $result->fetch_assoc();
        //valid the password supplied by the client to the password it should have in the database
        if (password_verify($testPass, $row['password']))
        {
            //return updated userArray with valid user data to login 
            $userArray['response'] = "Password verified. Access Granted.";
            $userArray['status'] = true;
            $userArray['test'] = "Password and username match, should allow access";
            $userArray['userID'] = $row['userID'];
            return $userArray;
        }
        //error catch for when the password is invalid
        else
        {   
            //no password given
            if ($userArray['password'] == "")
            {
                $userArray['response'] = "password must be supplied";
            }
            //password doesn't match what it should be
            else
            {
                $userArray['response'] = "Password does not match credentials for user";
            }       
            $userArray['status'] = false;
            return $userArray;
        }
    }
    //login fails if there are more than 1 query results that are returned, which means the username tried is incomplete or not unique
    if ($result->num_rows > 1)
    {
        $userArray['status'] = false;
        $userArray['response'] = "Error: No unique match found or no username given";
        return $userArray;
    }
    //if the query result is 0, then there are no usernames that match the query
    if ($result->num_rows < 1 || $result->num_rows == 0)
    {
        $userArray['status'] = false;
        $userArray['response'] = "Error: username not found";
        return $userArray;
    }
}

?>