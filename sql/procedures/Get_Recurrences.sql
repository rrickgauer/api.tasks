DELIMITER $$
CREATE PROCEDURE `Get_Recurrences`(
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
    DECLARE eventID CHAR(36);
    
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
        FETCH cursor_events INTO eventID;
        
        -- if no more events exit the loop
        IF finished = 1 THEN
			LEAVE LOOP_PROCESS_EVENTS;
		END IF;
		
        CALL Get_Event_Recurrences(eventID, range_start, range_end, FALSE);
    
    END LOOP LOOP_PROCESS_EVENTS;
    CLOSE cursor_events;
    
    
    -- now all the event occurences are in the Temp_Event_Occurrence_Dates table
    -- select all those events and match them to the Events meta data
    SELECT 
		teod.event_id AS event_id, 
        e.name AS name,
		teod.occurs_on AS occurs_on,
        e.starts_at AS starts_at,
        IS_EVENT_COMPLETED(event_id, occurs_on) AS completed
	FROM
		Temp_Event_Occurrence_Dates teod
        LEFT JOIN Events e ON teod.event_id = e.id
	ORDER BY occurs_on ASC;
    
    DROP TABLE Temp_Event_Occurrence_Dates;
    
END$$
DELIMITER ;
