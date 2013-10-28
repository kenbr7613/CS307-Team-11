CREATE TABLE Courses (
	CourseID int NOT NULL  AUTO_INCREMENT,
	Department varchar(255) NOT NULL,
	Level varchar(255) NOT NULL,
	Title varchar(255),
	Credits int(1),
	PRIMARY KEY (CourseID)
);

CREATE TABLE CourseOfferings (
	CourseOfferingID int NOT NULL AUTO_INCREMENT,
	CourseID int NOT NULL,
	CRN varchar(6) NOT NULL,
	StartTime datetime,
	EndTime datetime,
	StartDate datetime,
	EndDate datetime,
	CourseType int NOT NULL,
	Instructor varchar(255),
	InstructorEmail varchar(255),
	Location int,
	Room varchar(255),
	SemesterCode varchar(255) NOT NULL,
	PRIMARY KEY (CourseOfferingID)
);

CREATE TABLE CoursePrereqs (
	CourseID int NOT NULL,
	PrereqGroup int NOT NULL,
	PrereqCourseID int NOT NULL
);

CREATE TABLE CourseLinkedSections (
	CourseOfferingID int NOT NULL,
	LinkGroup int NOT NULL,
	LinkedCourseOFferingID int NOT NULL
);

CREATE TABLE Users (
	UserID int NOT NULL AUTO_INCREMENT,
	Username varchar(255) NOT NULL,
	Password varchar(255) NOT NULL,
	FirstName varchar(255),
	LastName varchar(255),
	College1 int NOT NULL,
	College2 int,
	College3 int,
	Major1 int NOT NULL,
	Major2 int,
	Major3 int,
	Minor1 int,
	Minor2 int,
	Minor3 int,
	PRIMARY KEY (UserID)
);

CREATE TABLE UserCoursesCompleted (
	UserID int NOT NULL,
	CourseID int NOT NULL
);

CREATE TABLE UserSchedule (
	UserID int NOT NULL,
	CourseOfferingID int NOT NULL
);

CREATE TABLE UserFriends (
	UserID int NOT NULL,
	FriendUserID int NOT NULL
);

CREATE TABLE DegreeRequirements (
	DegreeID int NOT NULL,
	RequirementGroupID int NOT NULL,
	RequirementDesc varchar(255)
);

CREATE TABLE RequirementGroups (
	GroupID int NOT NULL,
	CourseID int NOT NULL
);

CREATE TABLE Locations (
	LocationID int NOT NULL AUTO_INCREMENT,
	Lon int NOT NULL,
	Lat int NOT NULL,
	PRIMARY KEY (LocationID)
);
