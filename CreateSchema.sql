drop database if exists mp;
create database mp;
use mp;


-- const reference table
DROP TABLE IF EXISTS `important quality`;
CREATE TABLE `important quality` (
	`important quality` varchar(50),

	PRIMARY KEY(`important quality`)
);

INSERT INTO `important quality`
(`important quality`)
VALUES
	('major'),
	('gender'),
	('race'),
	('second language'),
	('religious affiliation'),
	('interested in diversity groups'),
	('hobbies')
;

-- We DON'T need a table pairing `important quality` to `participant` because `participant` always has a fixed number of important qualities represented as atomic columns within the `participant` table

DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
	`is active`                       boolean     NOT NULL,
	`is mentor`                       boolean     NOT NULL,
	`max matches`                     enum(
		'1',
		'2',
		'3'
	)                                             NOT NULL,
	`first name`                      varchar(64) NOT NULL,
	`last name`                       varchar(64) NOT NULL,
	`starid`                          char(8)     NOT NULL,
	`gender`                          enum(
		'male',
		'female',
		'non-binary',
		'other',
		'prefer not to answer'
	)                                             NOT NULL,
	`major`                           enum(
		'biology (allied health or cell molecular)',
		'biology (ecology or environmental science)',
		'biology (medical lab science)',
		'biology (radiography)',
		'chemistry',
		'general engineering',
		'composite materials engineering',
		'computer science',
		'data science',
		'geoscience',
		'math',
		'physics',
		'statistics',
		'undecided'
	)                                             NOT NULL,
	`pre program`                     enum(
		'dentistry',
		'forensics',
		'medicine',
		'occupational therapy',
		'optometry',
		'pharmacy',
		'physical therapy',
		'physician assistant',
		'none/not applicable'
	)                                             NOT NULL,
	`religious affiliation`           enum(
		'christianity',
		'judaism',
		'islam',
		'buddhism',
		'hinduism',
		'taoism',
		'spiritual but not religious',
		'agnostic',
		'atheist',
		'pastafarian',
		'other',
		'prefer not to answer',
		"doesn't matter"
	)                                             NOT NULL,
	`international student`           boolean     NOT NULL default false,
	`lgbtq+`                          boolean     NOT NULL default false,
	`student athlete`                 boolean     NOT NULL default false,
	`multilingual`                    boolean     NOT NULL default false,
	`not born in this country`        boolean     NOT NULL default false,
	`transfer student`                boolean     NOT NULL default false,
	`first gen college student`       boolean     NOT NULL default false,
	`unsure or undecided about major` boolean     NOT NULL default false,
	`interested in diversity groups`  boolean     NOT NULL default false,
	`preferred gender`                enum(
		'male',
		'female',
		'non-binary',
		'other',
		'prefers not to answer',
		"doesn't matter"
	)                                             NOT NULL,
	`preferred race`                            enum(
		'white',
		'black',
		'asian',
		'hispanic',
		'aboriginal',
		'native american',
		'pacific islander',
		'other',
		'prefers not to answer',
		"doesn't matter"
	)                                             NOT NULL,
	`preferred religious affiliation`           enum(
		'christianity',
		'judaism',
		'islam',
		'buddhism',
		'hinduism',
		'taoism',
		'spiritual but not religious',
		'agnostic',
		'atheist',
		'pastafarian',
		'other',
		'prefers not to answer',
		"doesn't matter"
	)                                              NOT NULL,
	`1st most important quality`      varchar(50)  NOT NULL,
	`2nd most important quality`      varchar(50)  NOT NULL,
	`3rd most important quality`      varchar(50)  NOT NULL,
	`misc info`                       varchar(200) NOT NULL,

	PRIMARY KEY (starid),

	FOREIGN KEY(`1st most important quality`) REFERENCES `important quality`(`important quality`) on delete cascade,
	FOREIGN KEY(`2nd most important quality`) REFERENCES `important quality`(`important quality`) on delete cascade,
	FOREIGN KEY(`3rd most important quality`) REFERENCES `important quality`(`important quality`) on delete cascade
);


-- const reference table
DROP TABLE IF EXISTS `hobby`;
CREATE TABLE `hobby` (
	`id` int AUTO_INCREMENT,
	`hobby` varchar(26),

	PRIMARY KEY(`id`)
);

INSERT INTO `hobby`
(`hobby`)
VALUES
	('canoeing or kayaking'),
	('curling'),
	('cycling (road or mountain)'),
	('faith based events'),
	('hiking'),
	('hunting/fishing'),
	('movies'),
	('music/concerts'),
	('rock/ice climbing'),
	('running'),
	('intermural sports'),
	('video games'),
	('working out/gym'),
	('yoga'),
	('other')
;

DROP TABLE IF EXISTS `has hobby`;
CREATE TABLE `has hobby` (
	`starid` char(8),
	`hobby` varchar(26),

	FOREIGN KEY(starid) REFERENCES participant(starid) on delete cascade,
	-- FOREIGN KEY(hobby)  REFERENCES hobby(hobby)        on delete cascade,

	PRIMARY KEY(starid, hobby)
);


-- const reference table for second languages
DROP TABLE IF EXISTS `second language`;
CREATE TABLE `second language` (
	`id` int AUTO_INCREMENT,
	`second language` varchar(50),

	PRIMARY KEY(`id`)
);

INSERT INTO `second language`
(`second language`)
VALUES
	('american sign language'),
	('arabic'),
	('bangla'),
	('chinese'),
	('french'),
	('german'),
	('hindi/urdu'),
	('japanese'),
	('korean'),
	('russian'),
	('somali'),
	('spanish'),
	('thai'),
	('vietnamese'),
	('other')
;

DROP TABLE IF EXISTS `speaks second language`;
CREATE TABLE `speaks second language` (
	`starid` char(8),
	`second language` varchar(26),

	FOREIGN KEY(starid) REFERENCES participant(starid) on delete cascade,
	-- FOREIGN KEY(`second language`)  REFERENCES `second language`(`second language`) on delete cascade,

	PRIMARY KEY(starid, `second language`)
);

-- const reference table for races
DROP TABLE IF EXISTS `race`;
CREATE TABLE `race` (
	`id` int AUTO_INCREMENT,
	`race` varchar(50),

	PRIMARY KEY(`id`)
);

INSERT INTO `race`
(`race`)
VALUES
	('white'),
	('black'),
	('asian'),
	('hispanic'),
	('aboriginal'),
	('native american'),
	('pacific islander'),
	('other'),
	('prefer not to answer')
;

DROP TABLE IF EXISTS `is race`;
CREATE TABLE `is race` (
	`starid` char(8),
	`race` varchar(17),

	FOREIGN KEY(starid) REFERENCES participant(starid) on delete cascade,
	-- FOREIGN KEY(`race`)  REFERENCES `race`(`race`) on delete cascade,

	PRIMARY KEY(starid, `race`)
);


DROP TABLE IF EXISTS `mentorship`;
CREATE TABLE `mentorship` (
	`mentor starid`  char(8),
	`mentee starid`  char(8),
	`start date`    datetime,
	`end date`      datetime,

	PRIMARY KEY(`mentor starid`, `mentee starid`),

	FOREIGN KEY(`mentor starid`) REFERENCES participant(starid) on delete cascade,
	FOREIGN KEY(`mentee starid`) REFERENCES participant(starid) on delete cascade
);
