<?php   

class EventStruct {
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