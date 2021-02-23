DELIMITER $$
CREATE FUNCTION `format_date_display`(
	original_date DATE
) RETURNS CHAR(100) CHARSET UTF8 COLLATE UTF8_UNICODE_CI
    DETERMINISTIC
BEGIN
	DECLARE date_formatted VARCHAR(100);
    SET date_formatted = DATE_FORMAT(original_date, "%c/%d/%Y");
    RETURN (date_formatted);
END$$
DELIMITER ;
