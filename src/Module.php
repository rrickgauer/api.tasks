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
        } else {
            $this->setEvent($eventID);
        }

        Common::printJson($this->data);
        Common::returnSuccessfulGet();
    }
    
    
    /********************************************************
    Retrieves all the events for a user from the database
    *********************************************************/
    protected function setEvents() {
        $eventsData = DB::getEvents($this->userID)->fetchAll(PDO::FETCH_ASSOC);
        $this->data = $eventsData;
    }

    /********************************************************
    Set's the data field to the meta data retrieved for 1 event
    *********************************************************/
    protected function setEvent($eventID) {
        $eventData = DB::getEvent($eventID)->fetch(PDO::FETCH_ASSOC);
        $this->data = $eventData;
    }
}






































?>





