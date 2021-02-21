<?php 
//Name: Austin Klevgaard - ICA06 - Submissions Code : 1201_2500_A06 
//database info:
//username: aklevgaa_aklevgaa
//pass: TastyTomato95

//also:
//username: aklevgaa_testuser
//pass: Testpass123

// Set to always get fresh page processing, no caches supplied
header("Cache-Control: no-cache, must re-validate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_start();

$mysqli = null;
$mysqli_status = "";

mysqliConnect();    //starts the connection and initiates the connection assigning $mysqli object


function mysqliConnect()
{
    global $mysqli; // register the global here for use

    //set up the $mysqli object to interact with the database
    //$mysqli = new mysqli(DB_Host, DB_user, DB_Password, DB, Name, [DB_Port], [BP_Unix_Socket])
    $mysqli = new mysqli("localhost", "aklevgaa_aklevgaa", "TastyTomato95", "aklevgaa_icaDB" );

    if ($mysqli->connect_errno)
    {
        $err_msg = "Connect Error({$mysqli->connect_errno}) : {$mysqli->connect_error}";
        error_log($err_msg);
        echo json_encode($err_msg);
        die();
    }
}
//query function that will attampt to return a set bound to the resulting object
function mysqliQuery($q)
{
    global $mysqli, $mysqli_status; //register globals

    $result = false;

    //checks the DB connection prior to query
    if ($mysqli->connect_errno)
    {
        $mysqli_status = "mysqliQuery 1: Error {$mysqli->errno} : {$mysqli->error}";
        error_log($mysqli_status);
        return $result;
    }

    if (!($result = $mysqli->query($q)))
    {
        //return of false is unseccessful, and will update the response status with an error message
        $mysqli_status = "mysqliQuery 2: Error {$mysqli->errno} : {$mysqli->error}";
        error_log($mysqli_status);
    }
    error_log($mysqli->info);
    return $result; //result object is returned to be processed, although it could be false
}


//NOn-Query function
//this will not resturn a set that can be expected to bind to a result object, it only provides true/false functionality
function mysqliNonQuery($q)
{
    global $mysqli, $mysqli_status; //register globals

    $result = 0; //initializes our result, representing the number of affeced rows = 0;

    //checks the DB connection prior to query
    if ($mysqli->connect_errno)
    {
        $mysqli_status = "mysqliQuery: Error {$mysqli->errno} : {$mysqli->error}";
        return -1;  //return -1 shows no connection 
    }

    if (!($result = $mysqli->query($q))) //exectures the query, assigns the results to $mysqli, and determines success 
    {
        //return value of false indication unsuccessful call, and will update the reponse
        $mysqli_status = "mysqliQuery: Error {$mysqli->errno} : {$mysqli->error}";
        return -1; //again, -1 indicates failure , 0+ indicates success
    }

    return $mysqli->affected_rows;  //returns the rows affected by the successful query
}

?>