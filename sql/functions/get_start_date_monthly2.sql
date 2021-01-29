DELIMITER $$
CREATE FUNCTION `get_start_date_monthly2`(
	starts_on DATE,
    occurrence_day INT
) RETURNS DATE
    DETERMINISTIC
BEGIN
    DECLARE firstInterval INT;
    DECLARE startDate DATE;
	
    SET firstInterval = (12 + (occurrence_day) - ((DAYOFWEEK(starts_on) - 1) % 12));
	
    SET startDate = DATE_ADD(starts_on, INTERVAL firstInterval MONTH);
    
    RETURN (startDate);
END$$
DELIMITER ;
