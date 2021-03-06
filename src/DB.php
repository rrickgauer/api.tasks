<?php

require_once('Events.php');

/************************************************************************
DB.php

This class is responsible for communicating with the database.
***********************************************************************/
class DB 
{
    /********************************************************
    Return a pdo database object
    *********************************************************/
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


    /********************************************************
    Insert a new user into the database
    *********************************************************/
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



    /********************************************************
    Get a user's password by checking the email and password combination.
    
    Returns false if the password passed in does not match the password
    retrieved from the database that matches the email passed in.
    *********************************************************/
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

    /********************************************************
    Retrieve user info.
    
     - id
     - email
     - created_on
    *********************************************************/
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


    /********************************************************
    Insert a new event
    *********************************************************/
    public static function insertEvent($user_id, $eventStruct) {
        $stmt = 'INSERT INTO Events (
            id, user_id, name, description, phone_number, 
            location_address_1, location_address_2, location_city, location_state, location_zip, 
            starts_on, ends_on, starts_at, ends_at, 
            frequency, seperation, count, until,
            recurrence_day, recurrence_week, recurrence_month
        )

        VALUES (
            :id, :user_id, :name, :description, :phone_number, 
            :location_address_1, :location_address_2, :location_city, :location_state, :location_zip, 
            :starts_on, :ends_on, :starts_at, :ends_at, 
            :frequency, :seperation, :count, :until,
            :recurrence_day, :recurrence_week, :recurrence_month
        )';

        $sql = DB::dbConnect()->prepare($stmt);

        // userID and event name cannot be null, so sanitize them
        $user_id = filter_var($user_id, FILTER_SANITIZE_STRING);
        $eventStruct->name = filter_var($eventStruct->name, FILTER_SANITIZE_STRING);
        
        // filter out the event fields if they aren't not null
        if ($eventStruct->id != null) 
            $eventStruct->id = filter_var($eventStruct->id, FILTER_SANITIZE_STRING);
        if ($eventStruct->description != null) 
            $eventStruct->description = filter_var($eventStruct->description, FILTER_SANITIZE_STRING);
        if ($eventStruct->phone_number != null) 
            $eventStruct->phone_number = filter_var($eventStruct->phone_number, FILTER_SANITIZE_STRING);
        if ($eventStruct->location_address_1 != null) 
            $eventStruct->location_address_1 = filter_var($eventStruct->location_address_1, FILTER_SANITIZE_STRING);
        if ($eventStruct->location_address_2 != null) 
            $eventStruct->location_address_2 = filter_var($eventStruct->location_address_2, FILTER_SANITIZE_STRING);
        if ($eventStruct->location_city != null) 
            $eventStruct->location_city = filter_var($eventStruct->location_city, FILTER_SANITIZE_STRING);
        if ($eventStruct->location_state != null) 
            $eventStruct->location_state = filter_var($eventStruct->location_state, FILTER_SANITIZE_STRING);
        if ($eventStruct->location_zip != null) 
            $eventStruct->location_zip = filter_var($eventStruct->location_zip, FILTER_SANITIZE_STRING);
        if ($eventStruct->starts_on != null) 
            $eventStruct->starts_on = filter_var($eventStruct->starts_on, FILTER_SANITIZE_STRING);
        if ($eventStruct->ends_on != null) 
            $eventStruct->ends_on = filter_var($eventStruct->ends_on, FILTER_SANITIZE_STRING);
        if ($eventStruct->starts_at != null) 
            $eventStruct->starts_at = filter_var($eventStruct->starts_at, FILTER_SANITIZE_STRING);
        if ($eventStruct->ends_at != null) 
            $eventStruct->ends_at = filter_var($eventStruct->ends_at, FILTER_SANITIZE_STRING);
        if ($eventStruct->frequency != null) 
            $eventStruct->frequency = filter_var($eventStruct->frequency, FILTER_SANITIZE_STRING);
        if ($eventStruct->seperation != null) 
            $eventStruct->seperation = filter_var($eventStruct->seperation, FILTER_SANITIZE_NUMBER_INT);
        if ($eventStruct->count != null) 
            $eventStruct->count = filter_var($eventStruct->count, FILTER_SANITIZE_NUMBER_INT);
        if ($eventStruct->until != null) 
            $eventStruct->until = filter_var($eventStruct->until, FILTER_SANITIZE_STRING);
        if ($eventStruct->recurrence_day != null) 
            $eventStruct->recurrence_day = filter_var($eventStruct->recurrence_day, FILTER_SANITIZE_NUMBER_INT);
        if ($eventStruct->recurrence_week != null) 
            $eventStruct->recurrence_week = filter_var($eventStruct->recurrence_week, FILTER_SANITIZE_NUMBER_INT);
        if ($eventStruct->recurrence_month != null) 
            $eventStruct->recurrence_month = filter_var($eventStruct->recurrence_month, FILTER_SANITIZE_NUMBER_INT);
            

        
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
        $sql->bindParam(':recurrence_day', $eventStruct->recurrence_day, PDO::PARAM_INT);
        $sql->bindParam(':recurrence_week', $eventStruct->recurrence_week, PDO::PARAM_INT);
        $sql->bindParam(':recurrence_month', $eventStruct->recurrence_month, PDO::PARAM_INT);

        $sql->execute();

        return $sql;
    }


    /********************************************************
    Returns all events belonging to a user within a range of dates.
    
    Calls the SQL procedure Get_Events().
    *********************************************************/
    public static function getRecurrences($userID, $startsOn, $endsOn) {
        $stmt = 'CALL Get_Recurrences(:userID, :startsOn, :endsOn)';
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

    /********************************************************
    Returns the meta-data for all of a user's events
    *********************************************************/
    public static function getEvents($userID) {
        $stmt = 'CALL Get_Events(:userID)';

        $sql = DB::dbConnect()->prepare($stmt);

        $userID = filter_var($userID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':userID', $userID, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }

    /********************************************************
    Returns the meta-data for 1 event
    *********************************************************/
    public static function getEvent($eventID) {
        $stmt = 'CALL Get_Event(:eventID)';

        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }


    /********************************************************
    Get the recurrences for 1 event
    *********************************************************/
    public static function getEventRecurrences($eventID, $startsOn, $endsOn) {
        $stmt = 'CALL Get_Event_Recurrences(:eventID, :startsOn, :endsOn, true)';
        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $startsOn = filter_var($startsOn, FILTER_SANITIZE_STRING);
        $sql->bindParam(':startsOn', $startsOn, PDO::PARAM_STR);

        $endsOn = filter_var($endsOn, FILTER_SANITIZE_STRING);
        $sql->bindParam(':endsOn', $endsOn, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }

    /********************************************************
    update an event
    *********************************************************/
    public static function updateEvent($eventID, $eventData) {
        $stmt = 'UPDATE Events SET 
            name               = :name,
            description        = :description,
            phone_number       = :phone_number,
            location_address_1 = :location_address_1,
            location_address_2 = :location_address_2,
            location_city      = :location_city,
            location_state     = :location_state,
            location_zip       = :location_zip,
            starts_on          = :starts_on,
            ends_on            = :ends_on,
            starts_at          = :starts_at,
            ends_at            = :ends_at,
            frequency          = :frequency,
            seperation         = :seperation,
            recurrence_day     = :recurrence_day,
            recurrence_week    = :recurrence_week,
            recurrence_month   = :recurrence_month
        WHERE id               = :eventID';

        $sql = DB::dbConnect()->prepare($stmt);


        // sanitize the parms
        $eventID  = filter_var($eventID, FILTER_SANITIZE_STRING);
        $eventData = Common::emptyStringsToNulls($eventData);
        $myInputs = filter_var_array($eventData);

        // bind the parms
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $sql->bindParam(':name', $eventData['name'], PDO::PARAM_STR);
        $sql->bindParam(':description', $eventData['description'], PDO::PARAM_STR);
        $sql->bindParam(':phone_number', $eventData['phone_number'], PDO::PARAM_STR);
        $sql->bindParam(':location_address_1', $eventData['location_address_1'], PDO::PARAM_STR);
        $sql->bindParam(':location_address_2', $eventData['location_address_2'], PDO::PARAM_STR);
        $sql->bindParam(':location_city', $eventData['location_city'], PDO::PARAM_STR);
        $sql->bindParam(':location_state', $eventData['location_state'], PDO::PARAM_STR);
        $sql->bindParam(':location_zip', $eventData['location_zip'], PDO::PARAM_STR);
        $sql->bindParam(':starts_on', $eventData['starts_on'], PDO::PARAM_STR);
        $sql->bindParam(':ends_on', $eventData['ends_on'], PDO::PARAM_STR);
        $sql->bindParam(':starts_at', $eventData['starts_at'], PDO::PARAM_STR);
        $sql->bindParam(':ends_at', $eventData['ends_at'], PDO::PARAM_STR);
        $sql->bindParam(':frequency', $eventData['frequency'], PDO::PARAM_STR);
        $sql->bindParam(':seperation', $eventData['seperation'], PDO::PARAM_INT);
        $sql->bindParam(':recurrence_day', $eventData['recurrence_day'], PDO::PARAM_INT);
        $sql->bindParam(':recurrence_week', $eventData['recurrence_week'], PDO::PARAM_INT);
        $sql->bindParam(':recurrence_month', $eventData['recurrence_month'], PDO::PARAM_INT);

        $sql->execute();
        return $sql;
    }

    /********************************************************
    delete an event
    *********************************************************/
    public static function deleteEvent($eventID) {
        $stmt = 'DELETE FROM Events where id = :eventID';
        $sql = DB::dbConnect()->prepare($stmt);

        // sanitize the parms
        $eventID  = filter_var($eventID, FILTER_SANITIZE_STRING);

        // bind the parms
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }


    /********************************************************
    update a recurrence
    *********************************************************/
    public static function updateRecurrence($eventID, $recurrenceData) {
        $stmt = 'UPDATE Event_Recurrences SET
              day      = :day,
              week     = :week,
              month    = :month
        WHERE event_id = :eventID';


        $sql = DB::dbConnect()->prepare($stmt);

        // recurrence and event ids cannot be null, so sanitize them
        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        
        $day = $recurrenceData['day'];
        if ($day != null) {
            $day = filter_var($day, FILTER_SANITIZE_NUMBER_INT);
        }

        $week = $recurrenceData['week'];
        if ($week != null) {
            $week = filter_var($week, FILTER_SANITIZE_NUMBER_INT);
        }

        $month = $recurrenceData['month'];
        if ($month != null) {
            $month = filter_var($month, FILTER_SANITIZE_NUMBER_INT);
        }


        // bind the parms
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $sql->bindParam(':day', $day, PDO::PARAM_INT);
        $sql->bindParam(':week', $week, PDO::PARAM_INT);
        $sql->bindParam(':month', $month, PDO::PARAM_INT);

        $sql->execute();
        return $sql;

    }

    /********************************************************
    Retrieve all the event completions for a user

    Returns:
        event_id
        name
        date
        marked_completed
    *********************************************************/
    public static function getCompletions(string $userID) {
        $stmt = 
        'SELECT 
            c.event_id as event_id,
            e.name as name,
            c.date as date,
            c.marked_completed as marked_completed
        FROM
            Event_Completions c
                LEFT JOIN Events e ON c.event_id = e.id
        WHERE 
	        event_id in (select e2.id from Events e2 where e2.user_id = :userID)
        ORDER BY 
            date DESC, 
            name ASC';

        $sql = DB::dbConnect()->prepare($stmt);

        $userID = filter_var($userID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':userID', $userID, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }

    /********************************************************
    Retrieve all the event completions for a single event

    Returns:
        event_id
        name
        date
        marked_completed
    *********************************************************/
    public static function getEventCompletions(string $eventID) {
        $stmt = 
        'SELECT 
            c.event_id as event_id,
            e.name as name,
            c.date as date,
            c.marked_completed as marked_completed
        FROM
            Event_Completions c
                LEFT JOIN Events e ON c.event_id = e.id
        WHERE 
	        c.event_id = :eventID
        ORDER BY 
            date DESC, 
            name ASC';

        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }

    /********************************************************
    Retrieve a single event completion on a specified date

    Returns:
        event_id
        name
        date
        marked_completed
    *********************************************************/
    public static function getEventCompletion($eventID, $date) {
        $stmt = 
        'SELECT 
            c.event_id as event_id,
            e.name as name,
            c.date as date,
            c.marked_completed as marked_completed
        FROM
            Event_Completions c
                LEFT JOIN Events e ON c.event_id = e.id
        WHERE 
	        c.event_id = :eventID
            AND c.date = :date
        ORDER BY 
            date DESC, 
            name ASC';

        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $date = filter_var($date, FILTER_SANITIZE_STRING);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }



    /********************************************************
    Insert a new event completion
    *********************************************************/
    public static function insertEventCompletion($eventID, $date) {
        $stmt = 
        'INSERT INTO Event_Completions
        (event_id, date) VALUES 
        (:eventID, :date)';

        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $date = filter_var($date, FILTER_SANITIZE_STRING);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }

    /********************************************************
    Delete an event completion
    *********************************************************/
    public static function deleteEventCompletion($eventID, $date) {
        $stmt = 
        'DELETE FROM Event_Completions
        WHERE event_id = :eventID
        AND date = :date';

        $sql = DB::dbConnect()->prepare($stmt);

        $eventID = filter_var($eventID, FILTER_SANITIZE_STRING);
        $sql->bindParam(':eventID', $eventID, PDO::PARAM_STR);

        $date = filter_var($date, FILTER_SANITIZE_STRING);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);

        $sql->execute();
        return $sql;
    }


}


?>