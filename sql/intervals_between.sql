DELIMITER $$
CREATE FUNCTION `intervals_between`( 
	date_start DATE,
    date_end DATE,
    frequency VARCHAR(20)
) RETURNS FLOAT
BEGIN

	DECLARE count FLOAT;
    
    IF date_start > date_end THEN
		SET count = 0;
        RETURN (count);
	END IF;
    
    
    CASE frequency
		WHEN 'DAILY' THEN
			SET count = ABS(TIMESTAMPDIFF(DAY, date_start , date_end));
		WHEN 'WEEKLY' THEN
			SET count = ABS(TIMESTAMPDIFF(WEEK, date_start , date_end));
		WHEN 'MONTHLY' THEN
			SET count = ABS(TIMESTAMPDIFF(MONTH, date_start , date_end));
		WHEN 'YEARLY' THEN
			SET count = ABS(TIMESTAMPDIFF(YEAR, date_start , date_end));
	END CASE;
    
    RETURN (count);


END$$
DELIMITER ;
