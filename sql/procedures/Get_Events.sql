DELIMITER $$
CREATE PROCEDURE `Get_Events`(
	IN user_id CHAR(36),
    IN range_start DATE,
    IN range_end DATE
)
BEGIN
	
    /*************************************************************
    This procedure generates the occurences of all events 
    between the given range_start and range_end.
    **************************************************************/
	
    DECLARE finished INT DEFAULT 0;
    DECLARE event_id CHAR(36);
    
    -- cursor for fetching all the event ids
	DECLARE cursor_events CURSOR 
    FOR SELECT e.id 
    FROM Events e 
    WHERE e.user_id = user_id;
    
	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
	FOR NOT FOUND SET finished = 1;
    
    -- this will hold all the event ids and their occurences
    CREATE TEMPORARY TABLE Temp_Event_Occurrence_Dates (
		event_id CHAR(36) NOT NULL,
        occurs_on DATE NOT NULL
	);
    
    
    OPEN cursor_events;
    
    -- for every event id, generate the event's occurrence dates
    LOOP_PROCESS_EVENTS: LOOP
		-- get the next event_id
        FETCH cursor_events INTO event_id;
        
        -- if no more events exit the loop
        IF finished = 1 THEN
			LEAVE LOOP_PROCESS_EVENTS;
		END IF;
		
        CALL Get_Event_Recurrence_Dates(event_id, range_start, range_end, FALSE);
    
    END LOOP LOOP_PROCESS_EVENTS;
    CLOSE cursor_events;
    
    
    -- now all the event occurences are in the Temp_Event_Occurrence_Dates table
    -- select all those events and match them to the Events meta data
    SELECT 
		teod.event_id AS event_id, 
		teod.occurs_on AS occurs_on,
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
        e.frequency AS frequency,
        e.seperation AS seperation,
        e.created_on AS created_on,
        er.id AS recurrence_id,
        er.day AS recurrence_day,
        er.week AS recurrence_week,
        er.month AS recurrence_month
	FROM
		Temp_Event_Occurrence_Dates teod
        LEFT JOIN Events e ON teod.event_id = e.id
        LEFT JOIN Event_Recurrences er ON teod.event_id = er.event_id
	ORDER BY occurs_on ASC;
    
    DROP TABLE Temp_Event_Occurrence_Dates;
    
END$$
DELIMITER ;
