/* Prepare MySQL Table for use in project */
/* File: HP_InitializeMySQL.sql           */
/* Author: Ron Fredericks, LectureMaker LLC */
/* Last Update: 7/10/2013 */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(60) NOT NULL,
  `is_admin` int(1) NOT NULL default '0',
  `session` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM;

INSERT INTO users (`username`, `password`, `email`, `is_admin`) VALUES 
( "Ron Fredericks", md5("rf"), "your@domain.com", 0 ), 
( "Tommy Tuba", md5("tt"), "your@domain.com", 0 ), 
("Admin", md5("aStrongPassword"), "your@domain.com", 1);
