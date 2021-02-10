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
}










































?>





