# Database Table Schema

This is the starting database table schema.

https://github.com/bakineggs/recurring_events_for

## Content

1. [Users](#users)
1. [Events](#events)
1. [Event_Recurrences](#event_recurrences)
1. [Event_Cancelations](#event_cancelations)
1. [Event_Completions](#event_completions)
1. [Event_Notes](#event_notes)



## Users

* id
* email
* password
* account_created_on

[:point_up: Back to top](#content)

## Events

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
* frequency (ONCE, DAILY, WEEKLY, MONTHLY, YEARLY)
* seperation
* count
* until

[:point_up: Back to top](#content)

## Event_Recurrences

* id 
* event_id
* month
* day
* week

[:point_up: Back to top](#content)

## Event_Cancelations

* id
* event_id
* date

[:point_up: Back to top](#content)

## Event_Completions

* id
* event_id
* date

[:point_up: Back to top](#content)

## Event_Notes

* id
* event_id
* content
* created_on

[:point_up: Back to top](#content)
