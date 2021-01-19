<?php

/************************************************************************

This is the main project file.

***********************************************************************/
require_once('Common-Functions.php');
require_once('DB.php');
require_once('Parser.php');
require_once('Constants.php');
require_once('User.php');

// setup the parser
$parser = new Parser();
$module = $parser->getModule();
$requestMethod = strtoupper($parser->getRequestMethod());


/**
 * Users section.
 */
if ($module == Constants::Modules['Users']) {

    // create a new user
    if ($requestMethod == Constants::RequestMethods['POST']) {
        // insert the user into the database
        $insertResult = DB::insertUser($_POST['email'], $_POST['password']);

        // error creating a new user
        if ($insertResult->rowCount() != 1) {
            Common::returnRequestNotFound('Email already exists.');
            exit;
        }

        // get the new user's id
        $userID = DB::getUserId($_POST['email'], $_POST['password']);
        
        // create a new user object
        $user = new User($userID);

        // return the user's data
        http_response_code(201);
        Common::printJson($user->getUserDataJson());
    }

    // Return a user's data
    if ($requestMethod == Constants::RequestMethods['GET']) {
        // create a new user by getting the user's id from the header
        $user = new User($parser->getUserId());

        // print the data
        http_response_code(200);
        Common::printJson($user->getUserDataJson());

    }
}



exit;



?>