<?php

class ReturnCode {
    public $errorNumber;
    public $message;

    public function __construct($errorNumber, $message) {
        $this->errorNumber = $errorNumber;
        $this->message = $message;
    }
}


class ReturnCodes {

    public $EmailExists;
    public $ErrorInsertNewUser;

    public function __construct() {
        $this->EmailExists = new ReturnCode(100, 'Email already exists');
        $this->ErrorInsertNewUser = new ReturnCode(101, 'Could not insert new user.');
    }


}





?>