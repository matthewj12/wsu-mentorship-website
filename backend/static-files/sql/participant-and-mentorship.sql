DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
	`first name`                       varchar(50)  NOT NULL,
	`last name`                        varchar(50)  NOT NULL,
	`starid`                           char(8)      NOT NULL,
	`graduation date`                  date         NOT NULL,
	`is active`                        boolean      NOT NULL,
	`is mentor`                        boolean      NOT NULL,
	`international student`            boolean      default false,
	`lgbtq+`                           boolean      default false,
	`student athlete`                  boolean      default false,
	`multilingual`                     boolean      default false,
	`not born in this country`         boolean      default false,
	`transfer student`                 boolean      default false,
	`first generation college student` boolean      default false,
	`unsure or undecided about major`  boolean      default false,
	`interested in diversity groups`   boolean      default false,
	`misc info`                        varchar(800),

	PRIMARY KEY (starid)
);


DROP TABLE IF EXISTS `mentorship`;
CREATE TABLE `mentorship` (
	`mentor starid`     char(8),
	`mentee starid`     char(8),
	`start date`        date,
	`end date`          date,
	`is extendable`     boolean,
	`earlier grad date` date,

	PRIMARY KEY(`mentor starid`, `mentee starid`),

	FOREIGN KEY(`mentor starid`) references participant(starid) on delete cascade,
	FOREIGN KEY(`mentee starid`) references participant(starid) on delete cascade
);

DROP TABLE IF EXISTS `admin code`;
CREATE TABLE `admin code` (
	`admin code` char(8),
	PRIMARY KEY (`admin code`)
);

INSERT INTO `admin code`(`admin code`) VALUES ('88111111');
