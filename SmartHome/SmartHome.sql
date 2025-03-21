create database SmartHome;
use SmartHome;

CREATE TABLE User (
	UID INT PRIMARY KEY,
	Password VARCHAR(255)
);

CREATE TABLE Event (
	EID INT PRIMARY KEY,
	UID INT,
	EName VARCHAR(255),
	EDate DATE NOT NULL,
	Start_Time TIME NOT NULL,
	Duration INT NOT NULL, -- Duration in minutes
	ERepeat VARCHAR(50),
	Temp_Upper DECIMAL(5,2),
	Temp_Lower DECIMAL(5,2),
	Lum_Upper DECIMAL(5,2),
	Lum_Lower DECIMAL(5,2),
	FOREIGN KEY (UID) REFERENCES User(UID) ON DELETE CASCADE
);

CREATE TABLE Notification (
	NID INT,
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
	AND E1.EDate = E2.EDate
	AND (E1.Start_Time < ADDTIME(E2.Start_Time, SEC_TO_TIME(E2.Duration * 60))
     	AND ADDTIME(E1.Start_Time, SEC_TO_TIME(E1.Duration * 60)) > E2.Start_Time);
    
	IF overlap_count > 0 THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Event time overlap detected in the same room';
	END IF;
END //

CREATE TRIGGER room_update
BEFORE UPDATE ON Room
FOR EACH ROW
BEGIN
	INSERT INTO Sensors (RID, DateTime, Luminosity, Temperature, Presence)
	VALUES (OLD.RID, OLD.DateTime, OLD.Luminosity, OLD.Temperature, OLD.Presence);
END;
//
DELIMITER ;


INSERT INTO User (UID, Password) VALUES (1, 'password123'), (2, 'securepass');

INSERT INTO Room (RID, DateTime, Luminosity, Temperature, Presence) VALUES
(1, '2025-03-14 08:00:00', 50.5, 22.5, TRUE),
(2, '2025-03-14 08:00:00', 40.0, 21.0, FALSE);

INSERT INTO Event (EID, UID, EName, EDate, Start_Time, Duration, ERepeat, Temp_Upper, Temp_Lower, Lum_Upper, Lum_Lower) VALUES
(1, 1, 'Morning Meeting', '2025-03-14', '09:00:00', 60, 'None', 25.0, 20.0, 60.0, 40.0),
(2, 1, 'Project Discussion', '2025-03-14', '10:30:00', 90, 'Weekly', 24.0, 19.0, 55.0, 35.0);

INSERT INTO Notification (NID, EID, DateTime, Error_Message) VALUES
(1, 1, '2025-03-14 08:30:00', 'Error at Room 2 Temperature sensor'),
(2, 2, '2025-03-14 10:15:00', 'Error at Light 1 Room 1');

INSERT INTO At (EID, RID) VALUES
(1, 1),
(2, 2);


INSERT INTO Light (RID, LID, DateTime, Intensity) VALUES
(1, 1, '2025-03-14 08:00:00', 70.0),
(2, 2, '2025-03-14 08:00:00', 50.0);

INSERT INTO Fan (RID, FID, DateTime, Fan_Speed) VALUES
(1, 1, '2025-03-14 08:00:00', 3),
(2, 2, '2025-03-14 08:00:00', 2);



