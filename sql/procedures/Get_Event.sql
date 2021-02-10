DELIMITER $$
CREATE PROCEDURE `Get_Event`(
	IN in_event_id CHAR(36)
)
BEGIN
	
SELECT 
	e.id AS id,
	e.name AS name,
	e.description AS description,
	e.phone_number AS phone_number,
	e.location_address_1 AS location_address_1,
	e.location_address_2 AS location_address_2,
	e.location_city AS location_city,
	e.location_state AS location_state,
	e.location_zip AS location_zip,
	e.starts_on AS starts_on,
	e.ends_on AS ends_on,
	e.starts_at AS starts_at,
	e.ends_at AS ends_at,
	e.frequency AS frequency,
	e.seperation AS seperation,
	e.count AS count,
	e.until AS until,
	e.created_on AS created_on
FROM 
	Events e
WHERE 
	e.id = in_event_id
LIMIT 1;
    
END$$
DELIMITER ;
