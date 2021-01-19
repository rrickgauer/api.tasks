<?php


/************************************************************************
 Common-Functions.php

 This class contains several common functions used throughout the project.

 Available functions:
 - printJson
 - returnBadRequest
 - returnRequestNotFound

***********************************************************************/

require_once('Constants.php');

class Common 
{

    /**
     * Formats and prints a php style array into a json format.
     */
    public static function printJson($data) {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Cache-Control: public');
        // http_response_code(200);

        echo json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE + JSON_NUMERIC_CHECK);
    }

    /**
     * Returns a bad request response.
     * 
     * Use when a url is not correct. Such as an invalid module.
     */
    public static function returnBadRequest($message = 'Invalid URL') {
        http_response_code(400);
        echo $message;
        exit;
    }

    /**
     * Use when the resource could not be found
     */
    public static function returnRequestNotFound($message = 'Resource not found!') {
        header('Content-Type: text/html; charset=UTF-8');
        http_response_code(404);
        echo $message;
        exit;
    }

}



?>