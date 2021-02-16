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


    public function post($newEventRecurrenceStruct = null) {
        $response = DB::insertEventRecurrence($newEventRecurrenceStruct);

        if ($response->rowCount() == 1) {
            parent::post();
        } else {
            Common::returnUnsuccessfulCreation();
        }

        exit;
    }

}






































?>





