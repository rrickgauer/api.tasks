<?php

require_once('DB.php');
require_once('Parser.php');
require_once('Return-Codes.php');
require_once('Constants.php');
require_once('Common-Functions.php');

/***************************************************************************
Module

This class is the parent of all the module children class.
****************************************************************************/
class Module
{
    /********************************************************
    Private/protected data members
    *********************************************************/
    protected $userID;
    protected $data;


    /********************************************************
    Default constructor
    *********************************************************/
    public function __construct(string $userID) {
        $this->userID = $userID;
    }


    /********************************************************
    abstract Methods
    *********************************************************/
    public function get() {
        Common::printJson($this->data);
        Common::returnSuccessfulGet();
    }

    public function post() {
        Common::returnSuccessfulCreation();
    }

    public function put($resourceID, $resourceBody) {
        Common::returnSuccessfulGet();
    }

    /********************************************************
    Access methods
    *********************************************************/
    public function getUserID() {
        return $this->userID;
    }

    public function setUserID($newUserID) {
        $this->userID = $newUserID;
    }

    public function getData() {
        return $this->data;
    }
}


/***************************************************************************
Events

This class handles all requests for the events module
****************************************************************************/
class Events extends Module
{
    /********************************************************
    Abstract implementation for GET
    *********************************************************/
    public function get($eventID = NULL) {
        if ($eventID == NULL) {
            $this->data = $this->getEvents();
        } else {
            $this->data = $this->getEvent($eventID);
        }

        parent::get();
    }
    
    
    /********************************************************
    Retrieves all the events for a user from the database
    *********************************************************/
    protected function getEvents() {
        $eventsData = DB::getEvents($this->userID)->fetchAll(PDO::FETCH_ASSOC);
        return $eventsData;
    }

    /********************************************************
    Set's the data field to the meta data retrieved for 1 event
    *********************************************************/
    protected function getEvent($eventID) {
        $eventData = DB::getEvent($eventID)->fetch(PDO::FETCH_ASSOC);
        return $eventData;
    }


    /********************************************************
    Post a new event
    *********************************************************/
    public function post($newEventData = null) {
        $dbResult = DB::insertEvent($this->userID, $newEventData);
        
        if ($dbResult->rowCount() == 1) {
            parent::post();
        } else {
            Common::returnUnsuccessfulCreation();
        }        
    }

    /********************************************************
    PUT request - update an event
    *********************************************************/
    public function put($eventID, $resourceBody) {
        $requiredEventFields = Constants::EventProperties;

        // get arrays of each of the keys
        $requiredEventFields = array_values($requiredEventFields);
        $resourceBodyKeys = array_keys($resourceBody);

        // check that each key in the required fields array is in the resource body
        $isFieldMissing = false;
        $missingFields = [];
        for ($count = 0; $count < count($requiredEventFields); $count++) {
            $key = $requiredEventFields[$count];

            if (!in_array($key, $resourceBodyKeys)) {
                $isFieldMissing = true;
                array_push($missingFields, $key);
            }
        }

        // request body was missing a required field
        if ($isFieldMissing) {
            Common::returnUnsuccessfulPut();

            $output = [
                "message" => "missing required fields",
                "missing_fields" => $missingFields,
            ];

            Common::printJson($output);
            exit;
        } 


        // update the db data
        $rc = DB::updateEvent($eventID, $resourceBody);

        if ($rc->rowCount() > 1) {
            http_response_code(500);
            exit;
        } else {
            http_response_code(200);
        }
    }

    /********************************************************
    DELETE request - delete an event
    *********************************************************/
    public function delete($eventID) {
        $dbResult = DB::deleteEvent($eventID);

        if ($dbResult->rowCount() == 1) {
            http_response_code(204);
        } else {
            Common::returnRequestNotFound();
        }
    }

}

/***************************************************************************
Recurrences

This class handles all requests for the recurrences module
****************************************************************************/
class Recurrences extends Module 
{
    /********************************************************
    Abstract implementation for GET
    *********************************************************/
    public function get($startsOn = null, $endsOn = null, $eventID = NULL) {
        $ReturnCodes = new ReturnCodes();

        // verify that both the starts_on and ends_on parms are set in the url
        if ($startsOn == null) {
            http_response_code(400);
            Common::printJson($ReturnCodes->Error_GetRecurrences_NoStartDate);    
            exit;
        } 
        else if ($endsOn == null) {
            http_response_code(400);
            Common::printJson($ReturnCodes->Error_GetRecurrences_NoEndDate);    
            exit;
        }

        // should api send recurrences for all events, or just 1?
        if ($eventID == NULL) {
            $this->data = $this->getRecurrences($startsOn, $endsOn);
        } else {
            $this->data = $this->getEventRecurrences($eventID, $startsOn, $endsOn);
        }

        parent::get();
    }
    
    
    /********************************************************
    Retrieves all recurrences for a user from the database
    between a set of dates.
    *********************************************************/
    protected function getRecurrences($startsOn, $endsOn) {
        $eventsData = DB::getRecurrences($this->userID, $startsOn, $endsOn)->fetchAll(PDO::FETCH_ASSOC);
        return $eventsData;
    }

    /********************************************************
    Retrieve all the recurrences for 1 event
    *********************************************************/
    protected function getEventRecurrences($eventID, $startsOn, $endsOn) {
        $eventData = DB::getEventRecurrences($eventID, $startsOn, $endsOn)->fetchAll(PDO::FETCH_ASSOC);
        return $eventData;
    }
}


class Completions extends Module
{

    /********************************************************
    Abstract implementation for GET
    *********************************************************/
    public function get($eventID = NULL) {
        if ($eventID == NULL) {
            $this->data = $this->getCompletions();
        } else {
            $this->data = $this->getEventCompletions($eventID);
        }

        parent::get();
    }

    /********************************************************
    Retrieve all the event completions for a user
    *********************************************************/
    protected function getCompletions() {
        $dbResult = DB::getCompletions($this->userID)->fetchAll(PDO::FETCH_ASSOC);
        return $dbResult;
    }

}






































?>





