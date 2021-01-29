DELIMITER $$
CREATE FUNCTION `get_first_monthweek_date`(
    range_start DATE,
    event_starts_on date,
    event_seperation int,
    event_recurrence_week int,
    event_recurrence_day int
) RETURNS date
    DETERMINISTIC
BEGIN
	
    /***************************************************************************
    get_first_monthweek_date retrieves the first date a "MonthWeek" type event
    can occur on/after the given range_start date
    ****************************************************************************/
    DECLARE first_date DATE;
    DECLARE day_of_month INT;
    DECLARE day_of_week int;
    DECLARE rangeStartFirstDayOfMonth DATE;
	
     SET first_date = event_starts_on;
    
    -- we need to get the first_date to first acceptable month the event can occur at
    -- keep adding the event_seperation of month intervals until the start_date is
    -- equal to or 1 event_seperation month interval past the range_start
    WHILE first_date < get_first_date_in_the_month(range_start) DO
		SET first_date = DATE_ADD(first_date, INTERVAL event_seperation MONTH);
    END WHILE;
    
    -- now that the first_date is in the first acceptable month the event can occur we need to set the first_date's day value
    -- first, we need to set the first_date to the first day of the month
    SET first_date = get_first_date_in_the_month(first_date);
	
    while DAYOFWEEK(first_date) - 1 <> event_recurrence_day do
		set first_date = date_add(first_date, interval 1 day);
	end while;
    
	-- set the date to the recurrence week value
	SET first_date = DATE_ADD(first_date, INTERVAL event_recurrence_week - 1 WEEK);
    
     return (first_date);
END$$
DELIMITER ;
