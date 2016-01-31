CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_has_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`role_id`),
  KEY `fk_user_has_role_role_id` (`role_id`),
  CONSTRAINT `fk_user_has_role_role_id` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  CONSTRAINT `fk_user_has_role_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user` (`id`, `login`, `email`, `password`) VALUES
	(3, 'meridius', 'email@gmail.com', '$2y$10$iwdSgsuoA2JY7eAczQd68qq40IAhvlfgb812iQlesTbvzNbELd7iq'),
	(4, 'karel', NULL, '$2y$10$gJz.nZEmGYFl/tJ2w3e5V.RyReEJ7eAczQd68qq40IAhvlfgb812i');

INSERT INTO `user_role` (`id`, `name`) VALUES
	(1, 'admin');

INSERT INTO `user_has_role` (`id`, `user_id`, `role_id`) VALUES
	(1, 3, 1);
