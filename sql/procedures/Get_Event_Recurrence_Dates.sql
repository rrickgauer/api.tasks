DELIMITER $$
CREATE PROCEDURE `Get_Event_Recurrence_Dates`(
	IN event_id CHAR(36),
    IN range_start DATE,
    IN range_end DATE,
    IN return_results BOOLEAN
)
SP: BEGIN
	/************************************************************************
    This procedure generates all of the dates an event will occur on 
    between the given range_start and range_end days. 
    *************************************************************************/

	-- Event row fields
    DECLARE event_starts_on DATE;
    DECLARE event_ends_on DATE;
    DECLARE event_seperation INT;
    DECLARE event_frequency ENUM('ONCE','DAILY','WEEKLY','MONTHLY','YEARLY');
    DECLARE event_recurrence_day INT;
    DECLARE event_recurrence_week INT;
    DECLARE event_recurrence_month INT;
    
    -- my fields
    DECLARE first_date DATE;
    DECLARE next_date DATE;
    DECLARE num_intervals INT;
    DECLARE helperInt INT;
    DECLARE month_start DATE;
    
    -- get all the event data from the Events table
	SELECT 
		e.starts_on, e.ends_on, e.seperation, e.frequency, er.day, er.week, er.month
	INTO event_starts_on , event_ends_on , event_seperation , event_frequency , event_recurrence_day, event_recurrence_week, event_recurrence_month 
	FROM 
		Events e
		LEFT JOIN Event_Recurrences er ON e.id = er.event_id
	WHERE
		e.id = event_id;
    
    -- make sure that range_start and range_end fall within the events starts_on and ends_on fields
    IF range_start > event_ends_on THEN
		LEAVE SP;
	ELSEIF range_end < event_starts_on THEN
		LEAVE SP;
	END IF;
    
	SET first_date = range_start;	-- initialize the first_date to the start of the range
    
    -- need to get the first date the event can occur on between the ranges following the event_seperation
    IF event_frequency = 'WEEKLY' THEN
		SET first_date = GET_START_DATE_WEEKLY(range_start, event_starts_on, event_seperation, event_recurrence_day);
	ELSEIF event_frequency = 'DAILY' THEN
		SET first_date = GET_START_DATE_DAILY(range_start, event_starts_on, event_seperation);
	ELSEIF event_frequency = 'MONTHLY' THEN
		IF event_recurrence_week IS NULL 
        AND event_recurrence_day IS NOT NULL THEN
			SET first_date = GET_FIRST_MONTHDAY_DATE(range_start, event_starts_on, event_seperation, event_recurrence_day);
		ELSE
			SET first_date = GET_FIRST_MONTHWEEK_DATE(range_start, event_starts_on, event_seperation, event_recurrence_week, event_recurrence_day);
		END IF;
	ELSE -- YEARLY
		SET first_date = GET_FIRST_YEARLY_DATE(range_start, event_starts_on, event_seperation, event_recurrence_month, event_recurrence_week, event_recurrence_day);
    END IF;
    
    -- set the next date to the first date
    SET next_date = first_date;
	
	-- create the temporary table if it does not exist
	CREATE TEMPORARY TABLE IF NOT EXISTS Temp_Event_Occurrence_Dates (
		event_id CHAR(36) NOT NULL,
		occurs_on DATE NOT NULL
	);

    
    SET num_intervals = event_seperation;
    
    -- generate the date the event occurs on
	WHILE 
		next_date <= range_end AND
        next_date <= event_ends_on
	DO
		-- add the date the result set
		INSERT INTO Temp_Event_Occurrence_Dates VALUES (event_id, next_date);
        
        -- get the next date depending on the event frequency
        IF event_frequency = 'WEEKLY' THEN
			SET next_date = DATE_ADD(next_date, INTERVAL num_intervals WEEK);
		ELSEIF event_frequency = 'DAILY' THEN
			SET next_date = DATE_ADD(next_date, INTERVAL num_intervals DAY);
        ELSEIF event_frequency = 'MONTHLY' THEN
			SET next_date = DATE_ADD(next_date, INTERVAL num_intervals MONTH);
            -- check if the recurrence is one that is a MONTHWEEK
            IF event_recurrence_week IS NOT NULL AND event_recurrence_day IS NOT NULL THEN
				SET next_date = GET_NEXT_MONTHWEEK_DATE(next_date, event_recurrence_week, event_recurrence_day);
            END IF;
		ELSE -- YEARLY
            SET next_date = DATE_ADD(next_date, INTERVAL event_seperation YEAR);
			-- check if the recurrence is one that is a MONTHWEEK
            IF event_recurrence_week IS NOT NULL AND event_recurrence_day IS NOT NULL THEN
				SET next_date = GET_NEXT_MONTHWEEK_DATE(next_date, event_recurrence_week, event_recurrence_day);
            END IF;
		END IF;
    END WHILE;
	
	-- if the caller wants to return the results,
    -- then we need to make the temp table to return the event's occurnce dates
    IF return_results = TRUE THEN
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
			e.location_zip AS location_zip
		FROM
			Temp_Event_Occurrence_Dates teod
			LEFT JOIN Events e ON teod.event_id = e.id
		ORDER BY occurs_on ASC;
    
		DROP TABLE Temp_Event_Occurrence_Dates;
	END IF;

END$$
DELIMITER ;
