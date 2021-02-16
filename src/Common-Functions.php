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
    /********************************************************
    Formats and prints a php style array into a json format.
    *********************************************************/
    public static function printJson($data) {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Cache-Control: public');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    /********************************************************
    Returns a bad request response.
    Use when a url is not correct. Such as an invalid module.
    *********************************************************/
    public static function returnBadRequest($message = 'Invalid URL') {
        http_response_code(400);
        echo $message;
    }


    /********************************************************
    Use when the resource could not be found
    *********************************************************/
    public static function returnRequestNotFound($message = 'Resource not found!') {
        header('Content-Type: text/html; charset=UTF-8');
        http_response_code(404);
        echo $message;
    }

    /********************************************************
    Use when a resource was successfully created
    *********************************************************/
    public static function returnSuccessfulCreation() {
        http_response_code(201);
    }


    /********************************************************
    Use when a resource was not successfully created.
    *********************************************************/
    public static function returnUnsuccessfulCreation() {
        http_response_code(422);
    }

    /********************************************************
    Successful request
    *********************************************************/
    public static function returnSuccessfulGet() {
        http_response_code(200);
    }
}



?>