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
	('primary major'),
	('secondary major'),
	('primary pre program'),
	('secondary pre program'),
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
	('polynesia/pacific islander'),
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
	('pastafarian'),
	('other'),
	('prefer not to answer'),
	("doesn't matter");



-- ___________________________ PARTICIPANT TABLE ___________________________



DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
	`is active`                        boolean      NOT NULL,
	`is mentor`                        boolean      NOT NULL,
	`first name`                       varchar(50)  NOT NULL,
	`last name`                        varchar(50)  NOT NULL,
	`starid`                           char(8)      NOT NULL,
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
	`mentor starid`  char(8),
	`mentee starid`  char(8),
	`start date`    datetime,
	`end date`      datetime,

	PRIMARY KEY(`mentor starid`, `mentee starid`),

	FOREIGN KEY(`mentor starid`) references participant(starid) on delete cascade,
	FOREIGN KEY(`mentee starid`) references participant(starid) on delete cascade
);


drop table if exists `important quality assoc tbl`;
create table `important quality assoc tbl` (
	`starid` char(8) NOT NULL,
	`important quality id` tinyint NOT NULL,
`important quality rank` tinyint NOT NULL,	primary key(`starid`, `important quality id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`important quality id`) references `important quality ref tbl`(`id`) on delete cascade
);


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


drop table if exists `hobby assoc tbl`;
create table `hobby assoc tbl` (
	`starid` char(8) NOT NULL,
	`hobby id` tinyint NOT NULL,
	primary key(`starid`, `hobby id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`hobby id`) references `hobby ref tbl`(`id`) on delete cascade
);


drop table if exists `primary major assoc tbl`;
create table `primary major assoc tbl` (
	`starid` char(8) NOT NULL,
	`primary major id` tinyint NOT NULL,
	primary key(`starid`, `primary major id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`primary major id`) references `major ref tbl`(`id`) on delete cascade
);


drop table if exists `secondary major assoc tbl`;
create table `secondary major assoc tbl` (
	`starid` char(8) NOT NULL,
	`secondary major id` tinyint NOT NULL,
	primary key(`starid`, `secondary major id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`secondary major id`) references `major ref tbl`(`id`) on delete cascade
);


drop table if exists `primary pre program assoc tbl`;
create table `primary pre program assoc tbl` (
	`starid` char(8) NOT NULL,
	`primary pre program id` tinyint NOT NULL,
	primary key(`starid`, `primary pre program id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`primary pre program id`) references `pre program ref tbl`(`id`) on delete cascade
);


drop table if exists `secondary pre program assoc tbl`;
create table `secondary pre program assoc tbl` (
	`starid` char(8) NOT NULL,
	`secondary pre program id` tinyint NOT NULL,
	primary key(`starid`, `secondary pre program id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`secondary pre program id`) references `pre program ref tbl`(`id`) on delete cascade
);


drop table if exists `race assoc tbl`;
create table `race assoc tbl` (
	`starid` char(8) NOT NULL,
	`race id` tinyint NOT NULL,
	primary key(`starid`, `race id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`race id`) references `race ref tbl`(`id`) on delete cascade
);


drop table if exists `religious affiliation assoc tbl`;
create table `religious affiliation assoc tbl` (
	`starid` char(8) NOT NULL,
	`religious affiliation id` tinyint NOT NULL,
	primary key(`starid`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`religious affiliation id`) references `religious affiliation ref tbl`(`id`) on delete cascade
);


drop table if exists `second language assoc tbl`;
create table `second language assoc tbl` (
	`starid` char(8) NOT NULL,
	`second language id` tinyint NOT NULL,
	primary key(`starid`, `second language id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`second language id`) references `second language ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred gender assoc tbl`;
create table `preferred gender assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred gender id` tinyint NOT NULL,
	primary key(`starid`, `preferred gender id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred gender id`) references `gender ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred hobby assoc tbl`;
create table `preferred hobby assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred hobby id` tinyint NOT NULL,
	primary key(`starid`, `preferred hobby id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred hobby id`) references `hobby ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred primary major assoc tbl`;
create table `preferred primary major assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred primary major id` tinyint NOT NULL,
	primary key(`starid`, `preferred primary major id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred primary major id`) references `major ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred secondary major assoc tbl`;
create table `preferred secondary major assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred secondary major id` tinyint NOT NULL,
	primary key(`starid`, `preferred secondary major id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred secondary major id`) references `major ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred primary pre program assoc tbl`;
create table `preferred primary pre program assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred primary pre program id` tinyint NOT NULL,
	primary key(`starid`, `preferred primary pre program id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred primary pre program id`) references `pre program ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred secondary pre program assoc tbl`;
create table `preferred secondary pre program assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred secondary pre program id` tinyint NOT NULL,
	primary key(`starid`, `preferred secondary pre program id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred secondary pre program id`) references `pre program ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred race assoc tbl`;
create table `preferred race assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred race id` tinyint NOT NULL,
	primary key(`starid`, `preferred race id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred race id`) references `race ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred religious affiliation assoc tbl`;
create table `preferred religious affiliation assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred religious affiliation id` tinyint NOT NULL,
	primary key(`starid`, `preferred religious affiliation id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred religious affiliation id`) references `religious affiliation ref tbl`(`id`) on delete cascade
);


drop table if exists `preferred second language assoc tbl`;
create table `preferred second language assoc tbl` (
	`starid` char(8) NOT NULL,
	`preferred second language id` tinyint NOT NULL,
	primary key(`starid`, `preferred second language id`),
	foreign key(`starid`) references `participant`(`starid`) on delete cascade,
	foreign key(`preferred second language id`) references `second language ref tbl`(`id`) on delete cascade
);
