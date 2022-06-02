drop database mp;
create database mp;
use `mp`;

DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
	`first name`                  varchar(64) NOT NULL,
	`last name`                   varchar(64) NOT NULL,
	`starid`                          char(8) NOT NULL,
	`gender`                            enum(
		'male',
		'female',
		'non-binary',
		'other',
		'prefer not to answer'
	)                                         NOT NULL,
	`major`                             enum(
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
	)                                         NOT NULL,
	`pre program`                       enum(
		'dentistry',
		'forensics',
		'medicine',
		'occupational therapy',
		'optometry',
		'pharmacy',
		'physical therapy',
		'physician assistant',
		'none/not applicable'
	)                                         NOT NULL,
	`second language`                   enum(
		'american sign language',
		'arabic',
		'bangla',
		'chinese',
		'french ',
		'german',
		'hindi/urdu',
		'japanese',
		'korean',
		'russian',
		'somali',
		'spanish',
		'thai',
		'vietnamese',
		'other',
		'none'
	)                                         NOT NULL,
	`race`                              enum(
		'white',
		'black',
		'asian',
		'hispanic',
		'aboriginal',
		'native american',
		'native hawaiian or pacific islander',
		'mixed',
		'other',
		'prefer not to answer'
	)                                         NOT NULL,
	`preferred gender`                  enum(
		'male',
		'female',
		'non-binary',
		'other',
		'prefers not to answer'
	)                                         NOT NULL,
	`religious affiliation`             enum(
		'christianity',
		'judaism',
		'islam',
		'buddhism',
		'hinduism',
		'taoism',
		'spiritual but not religious',
		'atheist',
		'pastafarian',
		'agnostic',
		'other'
	)                                         NOT NULL,
	`1st most important quality`        enum(
		'major and pre-professional program (if applicable)',
		'race',
		'language(s) in common',
		'hobbies in common',
		'interested in diversity groups',
		'religious affiliation'
	)                                         NOT NULL,
	`2nd most important quality`        enum(
		'major and pre-professional program (if applicable)',
		'race',
		'language(s) in common',
		'hobbies',
		'interested in diversity groups',
		'religious affiliation'
	)                                         NOT NULL,
	`3rd most important quality`        enum(
		'major and pre-professional program (if applicable)',
		'race',
		'language(s) in common',
		'hobbies',
		'interested in diversity groups',
		'religious affiliation'
	)                                         NOT NULL,
	`international student`           boolean NOT NULL,
	`lgbtq+`                          boolean NOT NULL,
	`student athlete`                 boolean NOT NULL,
	`multilingual`                    boolean NOT NULL,
	`not born in this country`        boolean NOT NULL,
	`transfer student`                boolean NOT NULL,
	`first gen college student`       boolean NOT NULL,
	`unsure or undecided about major` boolean NOT NULL,
	`interested in diversity groups`  boolean NOT NULL,
	`misc info`                  varchar(200) NOT NULL,
	PRIMARY KEY (`starid`)
);

-- const reference table
DROP TABLE IF EXISTS `hobby`;
CREATE TABLE `hobby` (
	`hobby` varchar(26),
	PRIMARY KEY(`hobby`)
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
	('other');

DROP TABLE IF EXISTS `has hobby`;
CREATE TABLE `has hobby` (
	`starid` char(8),
	`hobby` varchar(26),
	FOREIGN KEY(`starid`)
		REFERENCES `participant`(`starid`)
		on delete cascade,
	FOREIGN KEY(`hobby`)
		REFERENCES `hobby`(`hobby`)
		on delete cascade
);


DROP TABLE IF EXISTS `mentorship`;
CREATE TABLE `mentorship` (
	`mentor starid`  char(8),
	`mentee starid`  char(8),
	`start date`    datetime,
	`end date`      datetime,
	PRIMARY KEY (`mentor starid`, `mentee starid`)
);
