CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `account` (
   `id` int NOT NULL AUTO_INCREMENT,
   `user_id` int(11) unsigned NOT NULL,
   `is_active` int(2) unsigned NOT NULL DEFAULT 1,
   `account_balance` INT(11) NOT NULL DEFAULT 0,
   PRIMARY KEY (`id`),
   CONSTRAINT `fk_account_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `operation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from_user` int(11) unsigned NOT NULL,
  `to_user` int(11) unsigned NULL DEFAULT NULL,
  `type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `amount` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_transaction_from_user` FOREIGN KEY (`from_user`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transaction_to_user` FOREIGN KEY (`to_user`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user` (`first_name`, `last_name`, `email`) VALUES ('John', 'Doe', 'john@example.com');
INSERT INTO `user` (`first_name`, `last_name`, `email`) VALUES ('Jane', 'Doe', 'jane@example.com');
INSERT INTO `user` (`first_name`, `last_name`, `email`) VALUES ('Bobby', 'Doe', 'bobby@example.com');


INSERT INTO `account` (`user_id`, `is_active`, `account_balance`) VALUES ('1', '1', '80000');
INSERT INTO `account` (`user_id`, `is_active`, `account_balance`) VALUES ('2', '1', '80000');
INSERT INTO `account` (`user_id`, `is_active`, `account_balance`) VALUES ('3', '0', '0');


INSERT INTO `operation` (`from_user`, `to_user`, `type`, `amount`, `created_at`) VALUES ('1', '2', '1', '1000', '2020-05-23 16:19:44');

