<?php
/************************************************************************

This is the main project file.

***********************************************************************/
require_once('Common-Functions.php');
require_once('DB.php');
require_once('Parser.php');
require_once('Constants.php');
require_once('Return-Codes.php');
require_once('User.php');
// require_once('Events.php');
require_once('Module.php');

// setup the parser
$parser = new Parser();
$module = $parser->getModule();
$requestMethod = strtoupper($parser->getRequestMethod());

$ReturnCodes = new ReturnCodes();


// Common::printJson($_SERVER);
// exit;

/***************************************************************************
Users section.
****************************************************************************/
if ($module == Constants::Modules['Users']) {

    /**
     * Create a new user
     */
    if ($requestMethod == Constants::RequestMethods['POST']) {
        $userID = DB::getUserId($_POST['email'], $_POST['password']);

        // email already exists
        if ($userID != -1) {
            http_response_code(400);
            Common::printJson($ReturnCodes->Info_EmailExists);    
            exit;
        }


        // insert the user into the database
        $insertResult = DB::insertUser($_POST['email'], $_POST['password']);

        // error creating a new user
        if ($insertResult->rowCount() != 1) {
            http_response_code(400);
            Common::printJson($ReturnCodes->Error_InsertNewUser);    
            exit;
        }   

        // get the new user's id
        $userID = DB::getUserId($_POST['email'], $_POST['password']);
        
        // create a new user object
        $user = new User($userID);

        // return the user's data
        http_response_code(201);
        Common::printJson($user->getUserDataJson());
        exit;
    }

    /**
     * Return a user's data
     */
    else if ($requestMethod == Constants::RequestMethods['GET']) {
        
        if (isset($_GET['email'], $_GET['password'])) {
            $userID = DB::getUserId($_GET['email'], $_GET['password']);

            $user = new User($userID);
        } else {
            // get the user's info from the id passed in
            $user = new User($parser->getUserId());            
        }
        

        // print the data
        http_response_code(200);
        Common::printJson($user->getUserDataJson());

    }
}


/***************************************************************************
Events section.
****************************************************************************/
else if ($module == Constants::Modules['Events']) {
    $parser = new ParserEvents();
    $userID = $parser->getUserId();
    $eventsModule = new Events($userID);

    /**
     * GET the events for a user
     */
    if ($requestMethod == Constants::RequestMethods['GET']) {
        if (!$parser->isEventIDSet()) {
            $eventsModule->get();
        } else {
            $eventsModule->get($parser->getEventID());
        }
    }

    /**
     * POST a new event
     */
    else if ($requestMethod == Constants::RequestMethods['POST']) {
        $eventStruct = new EventStruct($parser->getNewEventRequestData());
        $eventStruct->id = $parser->getEventID();
        $eventsModule->post($eventStruct);

        exit;
    }

    /**
     * PUT an event
     */
    else if ($requestMethod == Constants::RequestMethods['PUT']) {
        $eventID = $parser->getEventID();
        $eventData = $parser->getPutData();
        $eventsModule->put($eventID, $eventData);

        exit;
    }

    
    /**
     * delete an event recurrence
     */
    else if ($requestMethod == Constants::RequestMethods['DELETE']) {
        $eventsModule->delete($parser->getEventID());
        exit;
    }


    exit;
}

/***************************************************************************
Recurrences section.
****************************************************************************/
else if ($module == Constants::Modules['Recurrences']) {
    $parser = new ParserRecurrences();
    $userID = $parser->getUserId();
    $recurrencesModule = new Recurrences($userID);

    /**
     * Get the event recurrences between a set of dates
     */
    if ($requestMethod == Constants::RequestMethods['GET']) {
        $startsOn = $parser->getDateStart();
        $endsOn = $parser->getDateEnd();

        if (!$parser->isEventIDSet()) {
            $recurrencesModule->get($startsOn, $endsOn);
        } else {
            $recurrencesModule->get($startsOn, $endsOn, $parser->getEventID());
        }

        exit;
    } 
    
    else {
        echo 'Invalid request method.';
        http_response_code(400);
        exit;
    }
}

/***************************************************************************
Completions section.
****************************************************************************/
else if ($module == Constants::Modules['Completions']) {
    $parser = new Parser();
    $userID = $parser->getUserId();
    $completetionsModule = new Completions($userID);

    /**
     * Get completions
     */
    if ($requestMethod == Constants::RequestMethods['GET']) {
        $eventID = $parser->getEventID();
        $date = $parser->getRequestedDate();

        $completetionsModule->get($eventID, $date);

        exit;
    } 

    /**
     * POST a completion
     */
    else if ($requestMethod == Constants::RequestMethods['POST']) {
        $newEventID = $parser->getEventID();
        $date = $parser->getRequestedDate();

        // verify the event id is included in the uri and the date is set
        if ($newEventID == null) {
            $output = [
                "message" => "missing the event_id in the URI",
            ];

            Common::printJson($output);
            Common::returnUnsuccessfulCreation();
            exit;
        } else if ($date == null) {
            $output = [
                "message" => "missing the date in the URI",
            ];

            Common::printJson($output);
            Common::returnUnsuccessfulCreation();
            exit;
        }

        // insert the date
        $completetionsModule->post($newEventID, $date);
        exit;
    }


    /**
     * POST a completion
     */
    else if ($requestMethod == Constants::RequestMethods['DELETE']) {
        $eventID = $parser->getEventID();
        $date = $parser->getRequestedDate();

        // verify the event id is included in the uri and the date is set
        if ($eventID == null) {
            $output = [
                "message" => "missing the event_id in the URI",
            ];

            Common::printJson($output);
            Common::returnUnsuccessfulCreation();
            exit;
        } else if ($date == null) {
            $output = [
                "message" => "missing the date field",
            ];

            Common::printJson($output);
            Common::returnUnsuccessfulCreation();
            exit;
        }

        // iremove the completion
        $completetionsModule->delete($eventID, $date);
        exit;
    }

    else {
        echo 'Invalid request method.';
        http_response_code(400);
        exit;
    }
}


exit;



?>