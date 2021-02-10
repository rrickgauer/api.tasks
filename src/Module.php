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
abstract class Module
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
    abstract protected function get();
    // abstract protected function post();
    // abstract protected function delete();
    // abstract protected function put();



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
            $this->setEvents();
        }

        Common::printJson($this->data);
        Common::returnSuccessfulGet();
    }
    
    
    /********************************************************
    Returns an array of events from the db
    *********************************************************/
    protected function getEvents() {
        // if the events hasn't been filled yet, do so
        if ($this->data == NULL) {
            $this->setEvents();    
        }

        return $this->data;
    }

    /********************************************************
    Retrieves all the events for a user from the database
    *********************************************************/
    protected function setEvents() {
        $eventsData = DB::getEvents($this->userID);

        $events = [];

        // fill the events array with Event objects
        while ($event = $eventsData->fetch(PDO::FETCH_ASSOC)) {
            array_push($events, new EventStruct($event));
        }

        $this->data = $events;
    }
}






































?>





