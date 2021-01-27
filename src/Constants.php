<?php

/************************************************************************
 Constants.php

 This class holds the constants throughout the project.
 ***********************************************************************/

class Constants 
{
    // types of request methods
    const RequestMethods = [
        "PUT" => "PUT",
        "POST" => "POST",
        "GET" => "GET",
        "DELETE" => "DELETE",
    ];

    // acceptable modules
    const Modules = [
        "Users" => "users",
        "Events" => "events",
    ];

    // properties for an event
    const EventProperties = [
        "id",
        "name",
        "description",
        "phone_number",
        "location_address_1",
        "location_address_2",
        "location_city",
        "location_state",
        "location_zip",
        "starts_on",
        "ends_on",
        "starts_at",
        "ends_at",
        "frequency",
        "seperation",
        "count",
        "until",
    ];


}




?>