# Recurring Events

This document lays out the signifigance of some of the database fields and how they are related to recurring events.


### Frequency

Frequency is the frequency at which this event recurs. 

Possible values: 'once', 'daily', 'weekly', 'monthly', and 'yearly'.

### Seperation

The number of intervals at en event's frequency in between occurrences of the event. 

For instance, if an event occurs every other week, it has a frequency of weekly and a separation of 2 because there are 2 weeks in between occurrences. 

This column defaults to 1.


## Event_Recurrences Table


### Daily Occurrences

This table is not used for daily recurring events.


### Weekly Occurrences

* **Week** = NULL
* **Day** - the day of the week the event occurs
  * 0 = Sunday, 1 = Monday, ..., 6 = Saturday
* **Month** = NULL


### Monthly Occurrences 

* **Week**
  * If non-NULL, this specifies the week of the month in which the event occurs. 
    * Positive numbers indicate the week from the start of the month.
      * 1 = 1st week of the month, 2 = 2nd week of the month, etc.
    * Negative numbers indicate the week before the end of the month.
      * -1 = last week of the month, -2 = 2nd to last week of the month, etc.
* **Day**
  * If the week column is NULL, the day column specifies the day of the month that the event occurs. 
  * If the week column is non-NULL, the day column specifies the day of the week that the event occurs in that week of the month.
* **Month** = NULL


### Yearly Occurrences 


* **Week**
  * If non-NULL, this specifies the week of the month in which the event occurs. 
    * Positive numbers indicate the week from the start of the month.
      * 1 = 1st week of the month, 2 = 2nd week of the month, etc.
    * Negative numbers indicate the week before the end of the month.
      * -1 = last week of the month, -2 = 2nd to last week of the month, etc.
* **Day**
  * If the week column is NULL, the day column specifies the day of the month that the event occurs. 
  * If the week column is non-NULL, the day column specifies the day of the week that the event occurs in that week of the month.
* **Month**
  * If the month column is non-NULL, it specifies the month for which this pattern should be used. 
  * If it is NULL, this pattern will be for the month of the original date/time of the event.


## Examples

These are some examples of what the fields would be set to for some given events.

### Example 1

*The event occurs every Thursday on the third week of the month.*

Field | Value
--- | ---
Seperation  | 1
Frequency | Monthly
Week  | 3
Day | 5
Month | NULL


### Example 2

*Event occurs on Saturday every six weeks.*

Field | Value
--- | ---
Seperation  | 6
Frequency | Weekly
Week  | NULL
Day | 6
Month | NULL


### Example 3

*Event occurs every 2 months on the 20th.*


Field | Value
--- | ---
Seperation  | 2
Frequency | Monthly
Week  | NULL
Day | 20
Month | NULL



### Example 4

*Event occurs on the second to last monday every six months.*

Field | Value
--- | ---
Seperation  | 6
Frequency | Monthly
Week  | -2
Day | 1
Month | NULL



