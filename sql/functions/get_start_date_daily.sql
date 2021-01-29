DELIMITER $$
CREATE FUNCTION `get_start_date_daily`(
	range_start DATE,
    starts_on DATE,
    event_seperation INT
) RETURNS DATE
    DETERMINISTIC
BEGIN
    DECLARE first_date DATE;
    SET first_date = starts_on;
    
    -- add seperation day intervals until the date is greater than or equal to the range start
    WHILE first_date < range_start DO
        SET first_date = DATE_ADD(first_date, INTERVAL event_seperation DAY);
    END WHILE;
    
    RETURN (first_date);
END$$
DELIMITER ;
