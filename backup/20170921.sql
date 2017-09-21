SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

create database stock027 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use stock027;

-- ----------------------------
--  Table structure for `daily_summary`
-- ----------------------------
DROP TABLE IF EXISTS `daily_summary`;
CREATE TABLE `daily_summary` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(10) DEFAULT NULL,
  `trade` varchar(10) DEFAULT NULL,
  `pricechange` varchar(10) DEFAULT NULL,
  `changepercent` varchar(10) DEFAULT NULL,
  `buy` varchar(10) DEFAULT NULL,
  `sell` varchar(10) DEFAULT NULL,
  `settlement` varchar(10) DEFAULT NULL,
  `open` varchar(10) DEFAULT NULL,
  `high` varchar(10) DEFAULT NULL,
  `low` varchar(10) DEFAULT NULL,
  `volume` varchar(10) DEFAULT NULL,
  `amount` varchar(10) DEFAULT NULL,
  `ticktime` varchar(10) DEFAULT NULL,
  `per` varchar(10) DEFAULT NULL,
  `pb` varchar(10) DEFAULT NULL,
  `mktcap` varchar(20) DEFAULT NULL,
  `nmc` varchar(20) DEFAULT NULL,
  `turnoverratio` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
