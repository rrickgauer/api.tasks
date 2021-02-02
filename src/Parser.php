<?php

/************************************************************************
 Parser.php

 This class is responsible for parsing the request url and header fields.
***********************************************************************/

require_once('Common-Functions.php');
require_once('DB.php');
require_once('Parser.php');
require_once('Constants.php');
require_once('Return-Codes.php');


class Parser
{
    protected $request;
    protected $module;
    protected $requestMethod;
    protected $userID;


    /**
     * Constructor.
     */
    public function __construct() {
        // Ensure a module is specified
        if (!isset($_SERVER['PATH_INFO'])) {
            Common::returnBadRequest('Module not specified.');
            exit;
        }

        // break up the request path 
        $this->request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
       
        $this->setModule();
        $this->setRequestMethod();
        $this->setUserId();
    }


    /**
     * Set the module.
     * 
     * Should always be the first element in the request array.
     */
    protected function setModule() {
        $module = $this->request[0];

        // make sure it's a valid module
        if (!in_array(strtolower($module), Constants::Modules)) {
            Common::returnBadRequest('Invalid module.');
            exit;
        }

        $this->module = $module;
    }

    /**
     * Return the module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * Set the request method (get, post, delete, put, etc...).
     */
    public function setRequestMethod() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // make sure it's an acceptable one
        if (!in_array(strtoupper($requestMethod), Constants::RequestMethods)) {
            Common::returnBadRequest('Invalid request method.');
            exit;
        }


        $this->requestMethod = $requestMethod;
    }

    /**
     * Return the request method.
     */
    public function getRequestMethod() {
        return $this->requestMethod;
    }

    /**
     * Set the user id from the X-USER-ID request header field.
     */
    protected function setUserId() {
        $userID = null;

        if (isset($_SERVER['HTTP_X_USER_ID'])) {
            $userID = $_SERVER['HTTP_X_USER_ID'];
        }

        $this->userID = $userID;
    }

    /**
     * Returns the user id from the header field.
     */
    public function getUserId() {
        return $this->userID;
    }
}



/************************************************************************
ParserEvents

This is a parser used for the events module. It is a child of the 
Parser class.

In addition to its parent methods, it also parses the start date and 
end dates.
***********************************************************************/
class ParserEvents extends Parser {

    protected $dateStart;
    protected $dateEnd;

    public function __construct() {
        parent::__construct();

        $this->setDateStart();
        $this->setDateEnd();

    }

    /**
     * Sets the date start.
     * 
     * Is passed in through the get request parm
     */
    protected function setDateStart() {
        $dateStart = null;

        // if the parm is not set dont worry about it
        if (isset($_GET['starts_on'])) {
            $dateStart = $_GET['starts_on'];
        }

        $this->dateStart = $dateStart;
    }

    /**
     * Sets the date end.
     * 
     * Is passed in through the get request parm
     */
    protected function setDateEnd() {
        $dateEnd = null;

        // if the parm is not set dont worry about it
        if (isset($_GET['ends_on'])) {
            $dateEnd = $_GET['ends_on'];
        }

        $this->dateEnd = $dateEnd;
    }

    /**
     * Returns the date start field
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * Returns the date end field
     */
    public function getDateEnd() {
        return $this->dateEnd;
    }

}




?>