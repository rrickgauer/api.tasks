DELIMITER $$
CREATE FUNCTION `get_first_yearly_date`(
    range_start DATE,
    event_starts_on DATE,
    event_seperation INT,
    event_recurrence_month INT,
    event_recurrence_week INT,
    event_recurrence_day INT
) RETURNS DATE
    DETERMINISTIC
BEGIN
	
    /***************************************************************************
    get_first_yearly_date retrieves the first date a "YEARLY" type event
    can occur on/after the given range_start date
    ****************************************************************************/
    DECLARE first_date DATE;
	DECLARE event_starts_on_month INT;
    DECLARE num_days_in_month INT;
	
	SET first_date = event_starts_on;
    
    -- is the recurrence month different than the month the event started on?
    -- if so, we need to set it to that
    IF event_recurrence_month IS NOT NULL THEN
        -- set the date to the specified event_recurrence_month
        SET event_starts_on_month = EXTRACT(MONTH FROM first_date);
        SET first_date = DATE_SUB(first_date, INTERVAL event_starts_on_month MONTH);
        SET first_date = DATE_ADD(first_date, INTERVAL event_recurrence_month MONTH);	-- now the date's month and year is set
    END IF;
    
    -- now we need to get the date into the range
    -- but following the event_seperation rule
    -- so we keep adding event_seperation year intervals till it reaches the range_start
    WHILE first_date < range_start DO
		SET first_date = DATE_ADD(first_date, INTERVAL event_seperation YEAR);
    END WHILE;
    
    -- now we need to set the date's day value
    IF event_recurrence_week IS NOT NULL 
    AND event_recurrence_day IS NOT NULL THEN
		SET first_date = GET_NEXT_MONTHWEEK_DATE(first_date, event_recurrence_week, event_recurrence_day);
	ELSE
		SET first_date = GET_FIRST_DATE_IN_THE_MONTH(first_date);
		
        SET num_days_in_month = DAY(LAST_DAY(first_date));	-- number of days in the month
        
        -- if the specified day of the month is larger than the number of actual days in the month
        -- (event_recurrence_day is 31, but the month is november (only has 30 days)) 
        -- set the day to the last day in the month
        IF event_recurrence_day > num_days_in_month THEN
			SET first_date = DATE_ADD(first_date, INTERVAL num_days_in_month DAY);
		ELSE
			SET first_date = DATE_ADD(first_date, INTERVAL event_recurrence_day DAY);
            SET first_date = DATE_ADD(first_date, INTERVAL -1 DAY);
        END IF;
        
    END IF;
    
    -- sometimes the date is still just before the range start
    -- so add 1 more seperation interval to it
    IF first_date < range_start THEN
		SET first_date = DATE_ADD(first_date, INTERVAL event_seperation YEAR);
    END IF;
    
    
     RETURN (first_date);
END$$
DELIMITER ;
