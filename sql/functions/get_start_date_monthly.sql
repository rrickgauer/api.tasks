DELIMITER $$
CREATE FUNCTION `get_start_date_monthly`(
	starts_on DATE,
    occurrence_day INT
) RETURNS DATE
    DETERMINISTIC
BEGIN
    DECLARE firstInterval INT;
    DECLARE startDate DATE;
	
    SET firstInterval = occurrence_day - EXTRACT(DAY FROM starts_on);
	
    SET startDate = DATE_ADD(starts_on, INTERVAL firstInterval DAY);
    
    RETURN (startDate);
END$$
DELIMITER ;
