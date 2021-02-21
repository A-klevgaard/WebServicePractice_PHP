<?php


// Wikipedia :
// Representational State Transfer (REST)
//  is a software architectural style that defines a set of constraints to be used 
//  for creating web services. Web services that conform to the REST architectural style, 
//  termed RESTful web services, provide interoperability between computer systems on the Internet.

abstract class API {

  // The HTTP method this request was made in, either GET, POST, PUT or DELETE
  protected $method = '';
  // The Model requested in the URI. eg: /files
  protected $endpoint = '';
  // An optional additional descriptor about the endpoint, used for things that can
  //   not be handled by the basic methods. eg: /files/process
  protected $verb = '';
  // Any additional URI components after the endpoint and verb have been removed, in our
  // case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
  // or /<endpoint>/<arg0>
  protected $args = Array();
  // Stores the input of the PUT request
  protected $file = Null;

  // Constructor: __construct
  // DIS- Allow for CORS ( Cross-Origin Resource Sharing )
  //  - assemble and pre-process the data
  public function __construct($request) {
    //header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    // split string into array defined by slash/
    $this->args = explode('/', rtrim($request, '/'));
    
    // "pop" first element off array and save the target/endpoint
    $this->endpoint = array_shift($this->args);
    
    // anything left thats not a number ? if so save to our optional extra verb argument
    if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) { 
      $this->verb = array_shift($this->args);
    }

    // Set the method : GET/POST/DELETE/PUT
    $this->method = $_SERVER['REQUEST_METHOD']; 
    
    // Since DELETE and PUT come in via a POST, check HTTP_X_HTTP_METHOD to see what it is
    if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
      if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
        $this->method = 'DELETE';
      } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
        $this->method = 'PUT';
      } else {
        throw new Exception("Unexpected Header");
      }
    }

    switch ($this->method) {
      case 'POST':
        $this->request = $this->_cleanInputs($_POST);
        break;
      case 'DELETE':
      case 'GET':
        $this->request = $this->_cleanInputs($_GET);
        break;
      case 'PUT':
        $this->request = $this->_cleanInputs($_GET); // WTF should be POST ?
        $this->file = file_get_contents("php://input");
        break;
      default:
        // Shouldn't get here, unless a PATCH or other unmatched is used
        echo $this->_response('Invalid Method', 405);
        die(); // actually want things to stop here.
        break;
    }
    // Initialization is complete, let dynamic processAPI function complete our
    // operation, relies on derived concrete class completion
  }

  // Dynamic "polymorphic" method, uses endpoint as method name, verify and invoke
  public function processAPI() {
    // if endpoint as methodname exists, invoke with arguments, return with
    //  processed response
    if (method_exists($this, $this->endpoint)) {
      return $this->_response($this->{$this->endpoint}($this->args));
    }
    return $this->_response("No Endpoint: $this->endpoint", 404);
  }

  // construct header, append processed json encoded response
  public function _response($data, $status = 200) { 
    header($_SERVER["SERVER_PROTOCOL"] . " " . $status . " " . $this->_requestStatus($status));
    return json_encode($data);
  }

  // utility method to sanitize inputs, remove all tags and trim white space out
  //  - recurse into embedded arrays
  private function _cleanInputs($data) {
    $clean_input = Array();
    if (is_array($data)) {
      foreach ($data as $k => $v) {
        $clean_input[$k] = $this->_cleanInputs($v);
      }
    } else {
      $clean_input = trim(strip_tags($data));
    }
    return $clean_input;
  }

  // utility method to populate header response codes, actually way more
  //  potential codes, these will suffice
  private function _requestStatus($code) {
    $status = array(
        200 => 'OK',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    );
    return ($status[$code]) ? $status[$code] : $status[500];
  }
}