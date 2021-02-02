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

        // echo json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE + JSON_NUMERIC_CHECK);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Returns a bad request response.
     * 
     * Use when a url is not correct. Such as an invalid module.
     */
    public static function returnBadRequest($message = 'Invalid URL') {
        http_response_code(400);
        echo $message;
    }

    /**
     * Use when the resource could not be found
     */
    public static function returnRequestNotFound($message = 'Resource not found!') {
        header('Content-Type: text/html; charset=UTF-8');
        http_response_code(404);
        echo $message;
    }

    /**
     * Use when a resource was successfully created
     */
    public static function returnSuccessfulCreation() {
        http_response_code(201);
    }

    /**
     * Use when a resource was not successfully created.
     */
    public static function returnUnsuccessfulCreation() {
        http_response_code(422);
    }


    /**
     * Returns an array with all of the event data sent to the api.
     * 
     * id - required - should be a uid
     * name - required
     * description
     * phone_number
     * location_address_1
     * location_address_2
     * location_city
     * location_state
     * location_zip
     * starts_on - required
     * ends_on
     * starts_at
     * ends_at
     * frequency
     * seperation
     * count
     * until
     */
    public static function getNewEventRequestData() {
        $newEventData = [];
        $eventKeys = array_values(Constants::EventProperties);  // event fields 

        // loop through the event fields constant to check and see if the key is in the post request data
        // if it is, add it to the array
        // otherwise, set it to null
        for ($count = 0; $count < count($eventKeys); $count++) {
            $key = $eventKeys[$count];

            if (isset($_POST[$key])) {
                $newEventData[$key] = $_POST[$key];
            } else {
                $newEventData[$key] = null;
            }

        }



        return $newEventData;
    }


}



?>