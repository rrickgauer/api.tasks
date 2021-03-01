DELIMITER $$
CREATE FUNCTION `is_event_completed`(
    in_event_id CHAR(36),
	in_completed_date DATE
) RETURNS TINYINT(1)
    DETERMINISTIC
BEGIN
    DECLARE num_records INT;
    DECLARE result BOOLEAN;
	SELECT COUNT(event_id) INTO num_records FROM Event_Completions WHERE event_id = in_event_id AND date = in_completed_date;
    
	IF num_records > 0 THEN
		SET result = TRUE;
	ELSE
		SET result = FALSE;
	END IF;
	
    RETURN (result);

END$$
DELIMITER ;
