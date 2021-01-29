
DELIMITER $$
CREATE FUNCTION `get_first_date_in_the_month`(
	original_date DATE
) RETURNS date
    DETERMINISTIC
BEGIN
    DECLARE first_date DATE;
	DECLARE day_of_month INT;
    
    SET first_date = original_date;
    
	SET day_of_month = EXTRACT(DAY FROM first_date) - 1;
	SET first_date = date_sub(first_date, INTERVAL day_of_month DAY);

    
    RETURN (first_date);
END$$
DELIMITER ;
