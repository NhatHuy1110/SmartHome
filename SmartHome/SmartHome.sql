create database SmartHome;
use SmartHome;
SET GLOBAL event_scheduler = ON;

CREATE TABLE User (
	UID INT AUTO_INCREMENT PRIMARY KEY,
	Username VARCHAR(255),
	Password VARCHAR(255)
);

CREATE TABLE Event (
	EID INT AUTO_INCREMENT PRIMARY KEY,
	UID INT,
	EName VARCHAR(255),
	EDate DATE NOT NULL,
	Start_Time TIME NOT NULL,
	Duration INT NOT NULL, -- Duration in minutes
	ERepeat VARCHAR(50),
	Status VARCHAR(3),
    Temp_Upper DECIMAL(5,2),
	Temp_Lower DECIMAL(5,2),
	Lum_Upper DECIMAL(5,2),
	Lum_Lower DECIMAL(5,2),
	FOREIGN KEY (UID) REFERENCES User(UID) ON DELETE CASCADE
);

CREATE TABLE Notification (
	NID INT AUTO_INCREMENT,
	EID INT,
	DateTime DATETIME NOT NULL,
	Error_Message TEXT,
	PRIMARY KEY (NID, EID),
	FOREIGN KEY (EID) REFERENCES Event(EID) ON DELETE CASCADE
);

CREATE TABLE Room (
	RID INT PRIMARY KEY,
	DateTime DATETIME,
	Luminosity DECIMAL(5,2),
	Temperature DECIMAL(5,2),
	Presence BOOLEAN
);

CREATE TABLE At (
	EID INT,
	RID INT,
	PRIMARY KEY (EID, RID),
	FOREIGN KEY (EID) REFERENCES Event(EID) ON DELETE CASCADE,
	FOREIGN KEY (RID) REFERENCES Room(RID) ON DELETE CASCADE
);

CREATE TABLE Light (
	RID INT,
	LID INT,
	DateTime DATETIME,
	Intensity DECIMAL(5,2),
	PRIMARY KEY (RID, LID, DateTime),
	FOREIGN KEY (RID) REFERENCES Room(RID) ON DELETE CASCADE
);

CREATE TABLE Fan (
	RID INT,
	FID INT,
	DateTime DATETIME,
	Fan_Speed INT,
	PRIMARY KEY (RID, FID, DateTime),
	FOREIGN KEY (RID) REFERENCES Room(RID) ON DELETE CASCADE
);

CREATE TABLE Sensors (
	RID INT,
	DateTime DATETIME,
	Luminosity DECIMAL(5,2),
	Temperature DECIMAL(5,2),
	Presence BOOLEAN,
	PRIMARY KEY (RID, DateTime),
	FOREIGN KEY (RID) REFERENCES Room(RID) ON DELETE CASCADE
);

DELIMITER //
CREATE TRIGGER check_event_overlap
BEFORE INSERT ON At
FOR EACH ROW
BEGIN
	DECLARE overlap_count INT;
	SELECT COUNT(*) INTO overlap_count
	FROM At A
	JOIN Event E1 ON A.EID = E1.EID
	JOIN Event E2 ON NEW.EID = E2.EID
	WHERE A.RID = NEW.RID
	AND (
        (E1.EDate = E2.EDate) OR 
        (E1.ERepeat = 'Daily' OR E2.ERepeat = 'Daily') OR
        (E1.ERepeat = 'Weekly' AND E2.ERepeat = 'Weekly' AND MOD(DATEDIFF(E1.EDate, E2.EDate), 7) = 0) OR
        (E1.ERepeat = 'Monthly' AND E2.ERepeat = 'Monthly' AND DAY(E1.EDate) = DAY(E2.EDate))
    )
	AND (E1.Start_Time < ADDTIME(E2.Start_Time, SEC_TO_TIME(E2.Duration * 60))
     	AND ADDTIME(E1.Start_Time, SEC_TO_TIME(E1.Duration * 60)) > E2.Start_Time);
    
	IF overlap_count > 0 THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Event time overlap detected in the same room';
	END IF;
END //

CREATE TRIGGER room_update
AFTER INSERT ON Sensors
FOR EACH ROW
BEGIN
    UPDATE Room
    SET Luminosity = NEW.Luminosity,
        Temperature = NEW.Temperature,
        Presence = NEW.Presence,
        DateTime = NEW.DateTime
    WHERE RID = NEW.RID;
END;
//

CREATE TRIGGER check_room_bounds
AFTER UPDATE ON Room
FOR EACH ROW
BEGIN
    DECLARE event_count INT;
    DECLARE event_eid INT;
    DECLARE temp_error TEXT;
    DECLARE lum_error TEXT;

    -- Check if there is a related event for the room
    SELECT COUNT(*), E.EID INTO event_count, event_eid
    FROM At A
    JOIN Event E ON A.EID = E.EID
    WHERE A.RID = NEW.RID
    AND (
        (E.EDate = DATE(NEW.DateTime)) OR
        (E.ERepeat = 'Daily') OR
        (E.ERepeat = 'Weekly' AND MOD(DATEDIFF(DATE(NEW.DateTime), E.EDate), 7) = 0) OR
        (E.ERepeat = 'Monthly' AND DAY(E.EDate) = DAY(DATE(NEW.DateTime)))
    )
    AND NEW.DateTime >= TIMESTAMP(E.EDate, E.Start_Time)
    AND NEW.DateTime <= TIMESTAMP(E.EDate, ADDTIME(E.Start_Time, SEC_TO_TIME(E.Duration * 60)))
    LIMIT 1;

    -- If there is a related event, check bounds
    IF event_count > 0 THEN
        SET temp_error = NULL;
        SET lum_error = NULL;

        -- Check temperature bounds
        IF NEW.Temperature > (SELECT Temp_Upper FROM Event WHERE EID = event_eid) THEN
            SET temp_error = CONCAT('Temperature exceeded upper bound in Room ', NEW.RID);
        ELSEIF NEW.Temperature < (SELECT Temp_Lower FROM Event WHERE EID = event_eid) THEN
            SET temp_error = CONCAT('Temperature fell below lower bound in Room ', NEW.RID);
        END IF;

        -- Check luminosity bounds
        IF NEW.Luminosity > (SELECT Lum_Upper FROM Event WHERE EID = event_eid) THEN
            SET lum_error = CONCAT('Luminosity exceeded upper bound in Room ', NEW.RID);
        ELSEIF NEW.Luminosity < (SELECT Lum_Lower FROM Event WHERE EID = event_eid) THEN
            SET lum_error = CONCAT('Luminosity fell below lower bound in Room ', NEW.RID);
        END IF;

        -- Insert notifications if errors exist
        IF temp_error IS NOT NULL THEN
            INSERT INTO Notification (NID, EID, DateTime, Error_Message)
            VALUES ((SELECT IFNULL(MAX(NID), 0) + 1 FROM Notification), event_eid, NOW(), temp_error);
        END IF;

        IF lum_error IS NOT NULL THEN
            INSERT INTO Notification (NID, EID, DateTime, Error_Message)
            VALUES ((SELECT IFNULL(MAX(NID), 0) + 1 FROM Notification), event_eid, NOW(), lum_error);
        END IF;
    END IF;
END;
//

CREATE EVENT notify_event_start
ON SCHEDULE EVERY 5 SECOND
DO
BEGIN
    INSERT INTO Notification (EID, DateTime, Error_Message)
    SELECT
        E.EID,
        NOW(),
        CONCAT('Event "', E.EName, '" has started in Room ', A.RID)
    FROM Event E
    JOIN At A ON E.EID = A.EID
    LEFT JOIN Notification N
        ON N.EID = E.EID
        AND TIMESTAMPDIFF(SECOND, TIMESTAMP(E.EDate, E.Start_Time), N.DateTime) BETWEEN 1 AND 7
        AND N.Error_Message LIKE 'Event "% has started in Room %'
    WHERE
        TIMESTAMPDIFF(SECOND, TIMESTAMP(E.EDate, E.Start_Time), NOW()) BETWEEN 1 AND 7
        AND N.EID IS NULL;
END;
//

DELIMITER ;

INSERT INTO User (UID,Username, Password) VALUES (1,'username1', 'password123'), (2,'username2', 'securepass');

INSERT INTO Room (RID, DateTime, Luminosity, Temperature, Presence) VALUES
(1, '2025-03-14 08:00:00', 50.5, 22.5, TRUE);

INSERT INTO Event (UID, EName, EDate, Start_Time, Duration, ERepeat, Status)
VALUES (1, 'Morning Routine', '2025-04-25', '08:00:00', 0, 'Daily', 'On');

INSERT INTO At (EID, RID) VALUES (1, 1);

INSERT INTO sensors (RID, DateTime, Luminosity, Temperature, Presence) VALUES
(1, '2025-03-14 08:00:00', 50.5, 22.5, TRUE);

