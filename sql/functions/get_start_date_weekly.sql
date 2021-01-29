DELIMITER $$
CREATE FUNCTION `get_start_date_weekly`(
	range_start DATE,
    event_starts_on DATE,
    event_seperation INT,
    event_recurrence_day INT
) RETURNS DATE
    DETERMINISTIC
BEGIN
	
    DECLARE first_date  DATE;
    DECLARE day_of_week INT;
    SET first_date = event_starts_on;
    
    WHILE first_date < range_start DO
		SET first_date = DATE_ADD(first_date, INTERVAL event_seperation WEEK);
    END WHILE;

    -- set the date's dayofweek to 0
    SET day_of_week = DAYOFWEEK(first_date) - 1;
    SET first_date = DATE_SUB(first_date, INTERVAL day_of_week DAY);
    
    
    SET first_date = DATE_ADD(first_date, INTERVAL event_recurrence_day DAY);
    
    IF first_date < range_start THEN
		SET first_date = DATE_ADD(first_date, INTERVAL event_seperation WEEK);
	END IF;
    
    RETURN (first_date);
END$$
DELIMITER ;
