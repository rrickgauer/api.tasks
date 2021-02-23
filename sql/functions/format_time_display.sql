DELIMITER $$
CREATE FUNCTION `format_time_display`(
	original_date DATETIME
) RETURNS CHAR(100) CHARSET UTF8 COLLATE UTF8_UNICODE_CI
    DETERMINISTIC
BEGIN
	DECLARE date_formatted VARCHAR(100);
    SET date_formatted = DATE_FORMAT(original_date, "%l:%i %p");
    RETURN (date_formatted);
END$$
DELIMITER ;
