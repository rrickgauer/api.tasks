<?php

include_once('DB.php');
include_once('Parser.php');
include_once('Return-Codes.php');
include_once('Constants.php');
include_once('Common-Functions.php');

/***************************************************************************
The Events class handles all the requests for events.
****************************************************************************/
class Events 
{
    protected $userID;
    protected $events;

    /***************************************************************************
    Default constructor
    ****************************************************************************/
    public function __construct($userID) {
        $this->userID = $userID;
        $this->events = NULL;
    }

    /***************************************************************************
    Returns an array of events from the db
    ****************************************************************************/
    public function getEvents() {
        // if the events hasn't been filled yet, do so
        if ($this->events == NULL) {
            $this->setEvents();    
        }

        return $this->events;
    }

    /***************************************************************************
    Retrieves all the events for a user from the database
    ****************************************************************************/
    protected function setEvents() {
        $eventsData = DB::getEvents($this->userID);

        $events = [];

        // fill the events array with Event objects
        while ($event = $eventsData->fetch(PDO::FETCH_ASSOC)) {
            array_push($events, new EventStruct($event));
        }

        $this->events = $events;
    }
}




/***************************************************************************
Interfaces with the recurrences db data
****************************************************************************/
class Recurrences extends Events
{
    protected $startsOn;
    protected $endsOn;
    protected $recurrences;

    /********************************************************
    Constructor
    *********************************************************/
    public function __construct($userID, $startsOn, $endsOn) {
        parent::__construct($userID);

        $this->recurrences = NULL;
        $this->startsOn = $startsOn;
        $this->endsOn = $endsOn;
    }


    /********************************************************
    Set the recurrences field with data from the db
    *********************************************************/
    protected function setRecurrences() {
        // get data from the database
        $recurrenceData = DB::getEventsRecurrences($this->userID, $this->startsOn, $this->endsOn)->fetchAll(PDO::FETCH_ASSOC);

        $this->recurrences = $recurrenceData;
    }

    /********************************************************
    Return the recurrences
    *********************************************************/
    public function getRecurrences() {
        // make sure the recurrences are set before returning them
        if ($this->recurrences == NULL) {
            $this->setRecurrences();
        }

        return $this->recurrences;
    }

}


/***************************************************************************
This is the data structure class for an event.
****************************************************************************/
class EventStruct 
{
    public $id;
    public $name;
    public $description;
    public $phone_number;
    public $location_address_1;
    public $location_address_2;
    public $location_city;
    public $location_state;
    public $location_zip;
    public $starts_on;
    public $ends_on;
    public $starts_at;
    public $ends_at;
    public $frequency;
    public $seperation;
    public $count;
    public $until;
    public $recurrence_id;
    public $recurrence_day;
    public $recurrence_week;
    public $recurrence_month;

    public function __construct($inDataArray) {
        error_reporting(E_ERROR | E_PARSE);

        $this->id                 = $inDataArray['id'];
        $this->name               = $inDataArray['name'];
        $this->description        = $inDataArray['description'];
        $this->phone_number       = $inDataArray['phone_number'];
        $this->location_address_1 = $inDataArray['location_address_1'];
        $this->location_address_2 = $inDataArray['location_address_2'];
        $this->location_city      = $inDataArray['location_city'];
        $this->location_state     = $inDataArray['location_state'];
        $this->location_zip       = $inDataArray['location_zip'];
        $this->starts_on          = $inDataArray['starts_on'];
        $this->ends_on            = $inDataArray['ends_on'];
        $this->starts_at          = $inDataArray['starts_at'];
        $this->ends_at            = $inDataArray['ends_at'];
        $this->frequency          = $inDataArray['frequency'];
        $this->seperation         = $inDataArray['seperation'];
        $this->count              = $inDataArray['count'];
        $this->until              = $inDataArray['until'];
        $this->recurrence_id      = $inDataArray['recurrence_id'];
        $this->recurrence_day     = $inDataArray['recurrence_day'];
        $this->recurrence_week    = $inDataArray['recurrence_week'];
        $this->recurrence_month   = $inDataArray['recurrence_month'];

    }
}














































?>


