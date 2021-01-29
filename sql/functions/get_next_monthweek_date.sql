DELIMITER $$
CREATE FUNCTION `get_next_monthweek_date`( 
	in_next_date DATE,
    event_recurrence_week INT,
    event_recurrence_day INT
) RETURNS DATE
BEGIN
	DECLARE next_date DATE;
    SET next_date = in_next_date;

	-- set it to the first of the month	
	SET next_date = GET_FIRST_DATE_IN_THE_MONTH(next_date);

	-- move it to the first day that's equal to the event_recurrence value
	WHILE DAYOFWEEK(next_date) - 1 <> event_recurrence_day DO
		SET next_date = DATE_ADD(next_date, INTERVAL 1 DAY);
	END WHILE;

	-- set the date to the recurrence week value
	SET next_date = DATE_ADD(next_date, INTERVAL event_recurrence_week - 1 WEEK);
	
    RETURN(next_date);

END$$
DELIMITER ;
