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
