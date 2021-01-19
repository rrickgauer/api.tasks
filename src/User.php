<?php

/************************************************************************
 DB.php

 This class represents a user. It holds the user's data such as email,
 id, created_on, and everything else.
***********************************************************************/

require_once('Common-Functions.php');
require_once('DB.php');
require_once('Parser.php');
require_once('Constants.php');



class User {

    private $id;
    private $email;
    private $createdOn;
    // private $password;

    /**
     * Constructor
     * 
     * Pass in the user id.
     */
    public function __construct($id) {
        $this->id = $id;

        // fetch the data from the database
        $dbResult = DB::getUser($this->id)->fetch(PDO::FETCH_ASSOC);

        $this->email = $dbResult['email'];
        $this->createdOn = $dbResult['created_on'];
    }

    /**
     * Return the user id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Return the user email.
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Return the user created_on
     */
    public function getCreatedOn() {
        return $this->createdOn;
    }

    /**
     * Returns all of the user info in an array format.
     */
    public function getUserDataJson() {
        $data = [];

        $data['id'] = $this->id;
        $data['email'] = $this->email;
        $data['createdOn'] = $this->createdOn;

        return $data;
    }



}








?>



