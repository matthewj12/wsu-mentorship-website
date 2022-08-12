drop database if exists `mp`;
create database `mp`;
use `mp`;

DROP TABLE IF EXISTS `participant`;
CREATE TABLE `participant` (
    `email` varchar(50) NOT NULL,
--     `verified?` tinyint(1) default 0,
    `verification code` varchar(255),
    `last signed in at` timestamp default current_timestamp(),
	PRIMARY KEY (`email`)
);

INSERT INTO `participant`(`email`) values ('11111111@go.minnstate.edu');
INSERT INTO `participant`(`email`) values ('ei@gmail.com');

select * from `participant`;

SELECT COUNT(*) FROM `participant`;