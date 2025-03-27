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
DELIMITER ;


INSERT INTO User (UID, Password) VALUES (1, 'password123'), (2, 'securepass');

INSERT INTO Room (RID, DateTime, Luminosity, Temperature, Presence) VALUES
(1, '2025-03-14 08:00:00', 50.5, 22.5, TRUE);


INSERT INTO sensors (RID, DateTime, Luminosity, Temperature, Presence) VALUES
(1, '2025-03-14 08:00:00', 50.5, 22.5, TRUE);

