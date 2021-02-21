<?php
//Austin Klevgaard
//Submission Code : 1201_2500_A06
require_once "./rest.api.php";
require_once "../inc/db.php";

// phpinfo();
// die();
class MyAPI extends API {

    // Since we don't allow CORS, we don't need to check Key Tokens
    // We will ensure that the user has logged in using our SESSION authentication
    // Constructor - use to verify our authentication, uses _response
    public function __construct($request, $origin) {
      parent::__construct($request);
  
      // Uncomment for authentication verification with your session
      //if (!isset($_SESSION["userID"]))
      //  return $this->_response("Get Lost", 403);
    }
  
    /**
     * Example of an Endpoint/MethodName 
     * - ie tags, messages, whatever sub-service we want
     * - IN this case test will be the endpoint,
     *   can have more than one for different rest calls
     *   should be named something appropriate - /restDir/cars.. etc
     */
    // protected function cars() {
    //   // TEST BLOCK - comment out once validation to here is verified
    //   $resp["method"] = $this->method;
    //   $resp["request"] = $this->request;
    //   $resp["putfile"] = $this->file;
    //   $resp["verb"] = $this->verb;
    //   $resp["args"] = $this->args;
      
    //   // comment out for proper functionality
    //   // return $resp; // Use this return to validate processing
    //   if( $this->method == "GET")
    //   {
    //     // verb will be filter
    //     return GetCars( $this->verb );
    //   }
    // }

    protected function messages() {
        // // TEST BLOCK - comment out once validation to here is verified
        // $resp["method"] = $this->method;
        // $resp["request"] = $this->request;
        // $resp["putfile"] = $this->file;
        // $resp["verb"] = $this->verb;
        // $resp["args"] = $this->args;
        
        // comment out for proper functionality
        // return $resp; // Use this return to validate processing
        if( $this->method == "GET")
        {
          // verb will be filter
          return GetMessages( $this->verb );
        }
        elseif ($this->method =="POST")
        {
          //return $this->request;
          return PostMessages($this->request['message']);
        }
        elseif ($this->method == "DELETE" && count($this->args) == 1)
        //..messages/messageID
        {
          return DeleteMessage($this->args[0]); //ID of delete request
        }
        else
        {
          $response = array();
          $response['status'] = "Operation not supported";
          return $response;
        }
      }
  }
  
  // // Add Endpoints for processing here as Methods for CRUD
  // function GetCars( $filter )
  // {
  //   $out = array();
  //   $out['filter'] = $filter;
  //   $out[$filter] = array( 'boxter', '911', '944');
  
  //   $jsonData = array();
  //   $jsonData['status'] = "Cars:GetCars:{$filter} - success";
  //   $jsonData['data'] = $out;
  
  //   return $jsonData;
  // }

  // Executable API Call
  // The actual functionality block here
  try {
    // Construct instance of our derived handler here
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    // invoke our dynamic method, should find the endpoint requested.
    echo $API->processAPI();
  } catch (Exception $e) { // OOPs - Houston, we have a problem
    echo json_encode(Array('error' => $e->getMessage()));
  }

// Add Endpoints for processing here as Methods for CRUD
function GetMessages( $filter )
{
    global $mysqli_status, $mysqli;

    $cleanedFilter = $mysqli->real_escape_string(strip_tags($filter));

    $query =  "SELECT messageID, users.username, message, messageTime FROM `messages` 
                INNER join users on messages.userID = users.userID
                where users.username like '%{$cleanedFilter}%' or messages.message like '%{$cleanedFilter}%'
                order by messageTime DESC"; 

    //query the database and record the results
    if ($result = mysqliQuery($query))
    {        
        $numRows = $result->num_rows;
        //error_log($numRows);
        $queryData = array();
        //copy the returned data into an array
        while ($row = $result->fetch_assoc())
        {
            $queryData[] = $row;
        }
        $status = "Retrieved : {$numRows} records";
    }
    else
    {
        $status = "QueryUsers Error : {$query} generated {$mysqli_status}";
    }
    //populate function response and return the data
    $OutputData = array();
    $OutputData['data'] = $queryData;
    $OutputData['status'] = $status;
    $OutputData['filter'] = $query;

    return $OutputData;
}
//Function that posts a message from the user to the database
function PostMessages($insert)
{
  global $mysqli_status, $mysqli; //load in mysql globals
  $response = array();  //array to send back to client

  //clean the user input and create the query
  $cleanedInsert = $mysqli->real_escape_string(strip_tags($insert));
  $userNum = $_SESSION['userID'];
  $query =  "INSERT INTO `messages`(`userID`,`message`) VALUES ({$userNum}, '{$cleanedInsert}')"; 

  $return = mysqliNonQuery($query); //insert row into the database

  //response was successful
  if ($return != -1)
  {
    $response['data'] = "Message POST successful. {$return} row(s) alterted.";
    $response['status'] = "Success";
  }
  //response did not suceed.
  else
  {
    $response['data'] = "Message failed to post to message board";
    $response['status'] = $mysqli_status;
    $response['query'] = $query;
  }
  //return data
  return $response;
}
//Function that deletes a message the database
function DeleteMessage($keyID)
{
  global $mysqli_status, $mysqli; //import globals for mysqli
  $response = array();  //create response array
  $cleanedKeyID = $mysqli->real_escape_string(strip_tags($keyID));  //clean user input
  $query = "DELETE FROM `messages` WHERE messageID = {$cleanedKeyID}";  //create mysqli query

  $return = mysqliNonQuery($query); //attempt to delete user message from databse
  //if delete is successful populate the returning response with success
  if ($return != -1)
  {
    $response['data'] = "Message DELETE successful. {$return} row(s) alterted.";
    $response['status'] = "Success";
  }
  //if delete fails then populate the returning response with failure and inform the user why
  else
  {
    $response['data'] = "Message failed to delete from message board";
    $response['status'] = $mysqli_status;
    $response['query'] = $query;
  }
  //return response data
  return $response;
}
