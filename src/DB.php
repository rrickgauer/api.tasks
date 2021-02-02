<?php

require_once('Event.php');

/************************************************************************
 DB.php

 This class is responsible for communicating with the database.
***********************************************************************/
class DB 
{
    /**
     * Return a pdo database object
     */
    public static function dbConnect() {
        include('db-info.php');
        
        try {
            // connect to database
            $pdo = new PDO("mysql:host=$host;dbname=$dbName",$user,$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
            
        } catch(PDOexception $e) {
            return 0;
        }
    }

    /**
     * Insert a new user into the database
     */
    public static function insertUser(string $email, string $password) {
        $stmt = 'INSERT INTO Users 
        (id, email, password, created_on) VALUES 
        (UUID(), :email, :password, NOW())';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);


        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        
        $sql->execute();

        return $sql;
    }

    /**
     * Get a user's password by checking the email and password combination.
     * 
     * Returns false if the password passed in does not match the password
     * retrieved from the database that matches the email passed in.
     */
    public static function getUserId(string $email, string $password) {
        $stmt = 'SELECT u.id, u.password FROM Users u WHERE u.email = :email LIMIT 1';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);

        $sql->execute();
        $resultSet = $sql->fetch(PDO::FETCH_ASSOC);

        if (!isset($resultSet['password'])) {
            return -1;
        }

        // check if the password matches the one in the database
        $hashedPassword = $resultSet['password'];

        if (!password_verify($password, $hashedPassword)) {
            return -1;
        } else {
            return $resultSet['id'];
        }
    }


    /**
     * Retrieve user info.
     * 
     * id
     * email
     * created_on
     */
    public static function getUser(string $id) {
        $stmt = 'SELECT 
        u.id,
        u.email,
        u.created_on
        FROM Users u
        WHERE u.id = :id 
        LIMIT 1';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':id', $id, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }


    /**
     * Insert a new event
     *  
     * id
     * user_id
     * name
     * link
     * description
     * phone_number
     * location_address_1
     * location_address_2
     * location_city
     * location_state
     * location_zip
     * starts_on
     * ends_on
     * starts_at
     * ends_at
     * frequency
     * seperation
     * count
     * until
     */
    public static function insertEvent($user_id, $eventStruct) {
        $stmt = 'INSERT INTO Events (
            id, user_id, name, description, phone_number, 
            location_address_1, location_address_2, location_city, location_state, location_zip, 
            starts_on, ends_on, starts_at, ends_at, 
            frequency, seperation, count, until
        )

        VALUES (
            :id, :user_id, :name, :description, :phone_number, 
            :location_address_1, :location_address_2, :location_city, :location_state, :location_zip, 
            :starts_on, :ends_on, :starts_at, :ends_at, 
            :frequency, :seperation, :count, :until
        )';



        $sql = DB::dbConnect()->prepare($stmt);

        // userID and event name cannot be null, so sanitize them
        $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
        $eventStruct->name = filter_var($eventStruct->name, FILTER_SANITIZE_STRING);
        
        // filter out the event fields if they aren't not null
        if (!is_null($eventStruct->id)) 
            $eventStruct->id = filter_var($eventStruct->id, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->description)) 
            $eventStruct->description = filter_var($eventStruct->description, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->phone_number)) 
            $eventStruct->phone_number = filter_var($eventStruct->phone_number, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->location_address_1)) 
            $eventStruct->location_address_1 = filter_var($eventStruct->location_address_1, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->location_address_2)) 
            $eventStruct->location_address_2 = filter_var($eventStruct->location_address_2, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->location_city)) 
            $eventStruct->location_city = filter_var($eventStruct->location_city, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->location_state)) 
            $eventStruct->location_state = filter_var($eventStruct->location_state, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->location_zip)) 
            $eventStruct->location_zip = filter_var($eventStruct->location_zip, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->starts_on)) 
            $eventStruct->starts_on = filter_var($eventStruct->starts_on, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->ends_on)) 
            $eventStruct->ends_on = filter_var($eventStruct->ends_on, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->starts_at)) 
            $eventStruct->starts_at = filter_var($eventStruct->starts_at, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->ends_at)) 
            $eventStruct->ends_at = filter_var($eventStruct->ends_at, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->frequency)) 
            $eventStruct->frequency = filter_var($eventStruct->frequency, FILTER_SANITIZE_STRING);
        if (!is_null($eventStruct->seperation)) 
            $eventStruct->seperation = filter_var($eventStruct->seperation, FILTER_SANITIZE_NUMBER_INT);
        if (!is_null($eventStruct->count)) 
            $eventStruct->count = filter_var($eventStruct->count, FILTER_SANITIZE_NUMBER_INT);
        if (!is_null($eventStruct->until)) 
            $eventStruct->until = filter_var($eventStruct->until, FILTER_SANITIZE_STRING);

        
        // bind the parms 
        $sql->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sql->bindParam(':id', $eventStruct->id, PDO::PARAM_STR);
        $sql->bindParam(':name', $eventStruct->name, PDO::PARAM_STR);
        $sql->bindParam(':description', $eventStruct->description, PDO::PARAM_STR);
        $sql->bindParam(':phone_number', $eventStruct->phone_number, PDO::PARAM_STR);
        $sql->bindParam(':location_address_1', $eventStruct->location_address_1, PDO::PARAM_STR);
        $sql->bindParam(':location_address_2', $eventStruct->location_address_2, PDO::PARAM_STR);
        $sql->bindParam(':location_city', $eventStruct->location_city, PDO::PARAM_STR);
        $sql->bindParam(':location_state', $eventStruct->location_state, PDO::PARAM_STR);
        $sql->bindParam(':location_zip', $eventStruct->location_zip, PDO::PARAM_STR);
        $sql->bindParam(':starts_on', $eventStruct->starts_on, PDO::PARAM_STR);
        $sql->bindParam(':ends_on', $eventStruct->ends_on, PDO::PARAM_STR);
        $sql->bindParam(':starts_at', $eventStruct->starts_at, PDO::PARAM_STR);
        $sql->bindParam(':ends_at', $eventStruct->ends_at, PDO::PARAM_STR);
        $sql->bindParam(':frequency', $eventStruct->frequency, PDO::PARAM_STR);
        $sql->bindParam(':seperation', $eventStruct->seperation, PDO::PARAM_INT);
        $sql->bindParam(':count', $eventStruct->count, PDO::PARAM_INT);
        $sql->bindParam(':until', $eventStruct->until, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }

    /**
     * Returs all events belonging to a user within a range of dates.
     * 
     * Calls the SQL procedure Get_Events().
     */
    public static function getEvents($userID, $startsOn, $endsOn) {
        $stmt = 'CALL Get_Events(:userID, :startsOn, :endsOn)';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $userID = filter_var($userID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':userID', $userID, PDO::PARAM_STR);

        $startsOn = filter_var($startsOn, FILTER_SANITIZE_STRING);
        $sql->bindParam(':startsOn', $startsOn, PDO::PARAM_STR);

        $endsOn = filter_var($endsOn, FILTER_SANITIZE_STRING);
        $sql->bindParam(':endsOn', $endsOn, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }



}


?>