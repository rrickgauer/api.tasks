DELIMITER $$
CREATE FUNCTION `get_first_monthday_date`(
    range_start DATE,
    event_starts_on date,
    event_seperation int,
    event_recurrence_day int
) RETURNS date
    DETERMINISTIC
BEGIN
	
    /***************************************************************************
    get_first_monthday_date retrieves the first date a "MonthDay" occurence
    can happen on/after the given range_start date
    ****************************************************************************/
    DECLARE first_date DATE;
    DECLARE day_of_month INT;

    SET first_date = event_starts_on;

    -- first, we need to set the first_date to the first day of the month
    SET day_of_month = EXTRACT(DAY FROM first_date);
    SET first_date = date_sub(first_date, INTERVAL day_of_month DAY);

    -- now we need to add the event_recurrence_day to the first_date
    -- to get it to be on the specific day of the month it occurs
    SET first_date = DATE_ADD(first_date, INTERVAL event_recurrence_day DAY);

    -- we need to get the first_date to first acceptable month the event can occur at
    -- keep adding the event_seperation of month intervals until the start_date is
    -- equal to or 1 event_seperation month interval past the range_start
    WHILE first_date < range_start DO
        SET first_date = DATE_ADD(first_date, INTERVAL event_seperation MONTH);
    END WHILE;

    -- all done!
    RETURN (first_date);
END$$
DELIMITER ;
