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
    $parserEvents = new ParserEvents();
    $userID = $parserEvents->getUserId();
    $eventsModule = new Events($userID);

    /**
     * Get the events for a user
     */
    if ($requestMethod == Constants::RequestMethods['GET']) {
        if (!$parserEvents->isEventIDSet()) {
            $eventsModule->get();
        } else {
            $eventsModule->get($parserEvents->getEventID());
        }
    }

    exit;
}

/***************************************************************************
Recurrences section.
****************************************************************************/
else if ($module == Constants::Modules['Recurrences']) {
    /**
     * Get the event recurrences between a set of dates
     */
    if ($requestMethod == Constants::RequestMethods['GET']) {
        $recurrenceParser = new ParserRecurrences();
        $recurrences = new Recurrences($recurrenceParser->getUserId(), $recurrenceParser->getDateStart(), $recurrenceParser->getDateEnd());

        Common::printJson($recurrences->getRecurrences());
        Common::returnSuccessfulGet();

        exit;
    }
}


exit;



?>