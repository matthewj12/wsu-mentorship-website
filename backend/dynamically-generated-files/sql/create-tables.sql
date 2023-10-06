DROP TABLE IF EXISTS `max matches ref tbl`;
CREATE TABLE `max matches ref tbl` (
	`id`          tinyint auto_increment,
	`max matches` char(1),
	primary key(`id`)
);

INSERT INTO `max matches ref tbl`
(`max matches`)
VALUES
	("1"),
	("2"),
	("3");



DROP TABLE IF EXISTS `important quality ref tbl`;
CREATE TABLE `important quality ref tbl` (
	`id`                tinyint auto_increment,
	`important quality` varchar(50),
	PRIMARY KEY(`id`)
);
INSERT INTO `important quality ref tbl`(`important quality`) VALUES
	('gender'),
	('major'),
	('pre program'),
	('religious affiliation'),
	('hobby'),
	('international student'),
	('lgbtq+'),
	('student athlete'),
	('multilingual'),
	('not born in this country'),
	('transfer student'),
	('first generation college student'),
	('interested in diversity groups'),
	('unused');



DROP TABLE IF EXISTS `hobby ref tbl`;
CREATE TABLE `hobby ref tbl` (
	`id`    tinyint AUTO_INCREMENT,
	`hobby` varchar(26),
	primary key(`id`)
);
INSERT INTO `hobby ref tbl`(`hobby`) VALUES
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



DROP TABLE IF EXISTS `second language ref tbl`;
CREATE TABLE `second language ref tbl` (
	`id`              tinyint AUTO_INCREMENT,
	`second language` varchar(50),
	PRIMARY KEY(`id`)
);
INSERT INTO `second language ref tbl`(`second language`) VALUES
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
	('other');



DROP TABLE IF EXISTS `race ref tbl`;
CREATE TABLE `race ref tbl` (
	`id`   tinyint AUTO_INCREMENT,
	`race` varchar(50),
	PRIMARY KEY(`id`)
);
INSERT INTO `race ref tbl`(`race`) VALUES
	('caucasian/white'),
	('black'),
	('asian'),
	('hispanic'),
	('aboriginal/native australian'),
	('indian/native american'),
	('polynesian/pacific islander'),
	('other'),
	('prefer not to answer');



drop table if exists `gender ref tbl`;
create table `gender ref tbl` (
	id       tinyint auto_increment,
	`gender` varchar(50),

	primary key(id)
);
insert into `gender ref tbl`(`gender`) values
	('male'),
	('female'),
	('non-binary'),
	('other'),
	('prefer not to answer');



drop table if exists `major ref tbl`;
create table `major ref tbl` (
	id      tinyint auto_increment,
	`major` varchar(50),
	primary key(id)
);
insert into `major ref tbl`(`major`) values
	('biology (allied health or cell molecular)'),
	('biology (ecology or environmental science)'),
	('biology (medical lab science)'),
	('biology (radiography)'),
	('chemistry'),
	('general engineering'),
	('composite materials engineering'),
	('computer science'),
	('data science'),
	('geoscience'),
	('math'),
	('physics'),
	('statistics'),
	('undecided');



drop table if exists `pre program ref tbl`;
create table `pre program ref tbl` (
	id            tinyint auto_increment,
	`pre program` varchar(50),
	primary key(id)
);
insert into `pre program ref tbl`(`pre program`) values
	('dentistry'),
	('forensics'),
	('medicine'),
	('occupational therapy'),
	('optometry'),
	('pharmacy'),
	('physical therapy'),
	('physician assistant'),
	('none/not applicable');



drop table if exists `religious affiliation ref tbl`;
create table `religious affiliation ref tbl` (
	id                      tinyint auto_increment,
	`religious affiliation` varchar(50),
	primary key(id)
);
insert into `religious affiliation ref tbl`(`religious affiliation`) values
	('christianity'),
	('judaism'),
	('islam'),
	('buddhism'),
	('hinduism'),
	('taoism'),
	('spiritual but not religious'),
	('agnostic'),
	('atheist'),
	('other'),
	('prefer not to answer'),
	("doesn't matter");



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



drop table if exists `max matches assoc tbl`;
create table `max matches assoc tbl` (
	`starid` char(8) NOT NULL,
	`max matches id` tinyint NOT NULL,
	primary key(`starid`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`max matches id`) references `max matches ref tbl`(`id`) on delete cascade
);


drop table if exists `gender assoc tbl`;
create table `gender assoc tbl` (
	`starid` char(8) NOT NULL,
	`gender id` tinyint NOT NULL,
	primary key(`starid`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`gender id`) references `gender ref tbl`(`id`) on delete cascade
);


drop table if exists `religious affiliation assoc tbl`;
create table `religious affiliation assoc tbl` (
	`starid` char(8) NOT NULL,
	`religious affiliation id` tinyint NOT NULL,
	primary key(`starid`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`religious affiliation id`) references `religious affiliation ref tbl`(`id`) on delete cascade
);


drop table if exists `important quality assoc tbl`;
create table `important quality assoc tbl` (
	`starid` char(8) NOT NULL,
	`important quality id` tinyint NOT NULL,
	`important quality rank` tinyint NOT NULL,
	primary key(`starid`, `important quality id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`important quality id`) references `important quality ref tbl`(`id`) on delete cascade
);


drop table if exists `hobby assoc tbl`;
create table `hobby assoc tbl` (
	`starid` char(8) NOT NULL,
	`hobby id` tinyint NOT NULL,
	primary key(`starid`, `hobby id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`hobby id`) references `hobby ref tbl`(`id`) on delete cascade
);


drop table if exists `major assoc tbl`;
create table `major assoc tbl` (
	`starid` char(8) NOT NULL,
	`major id` tinyint NOT NULL,
	primary key(`starid`, `major id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`major id`) references `major ref tbl`(`id`) on delete cascade
);


drop table if exists `pre program assoc tbl`;
create table `pre program assoc tbl` (
	`starid` char(8) NOT NULL,
	`pre program id` tinyint NOT NULL,
	primary key(`starid`, `pre program id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`pre program id`) references `pre program ref tbl`(`id`) on delete cascade
);


drop table if exists `race assoc tbl`;
create table `race assoc tbl` (
	`starid` char(8) NOT NULL,
	`race id` tinyint NOT NULL,
	primary key(`starid`, `race id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`race id`) references `race ref tbl`(`id`) on delete cascade
);


drop table if exists `second language assoc tbl`;
create table `second language assoc tbl` (
	`starid` char(8) NOT NULL,
	`second language id` tinyint NOT NULL,
	primary key(`starid`, `second language id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`second language id`) references `second language ref tbl`(`id`) on delete cascade
);
