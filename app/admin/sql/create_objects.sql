CREATE TABLE IF NOT EXISTS `[mpx]objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_czech_ci DEFAULT 'hybrid',
  `dev` enum('M','T','N','X') COLLATE utf8_czech_ci DEFAULT NULL,
  `origin` text COLLATE utf8_czech_ci DEFAULT NULL,
  `fs` int(11) NOT NULL,
  `fp` int(11) NOT NULL,
  `fr` int(11) NOT NULL,
  `fx` int(11) NOT NULL,
  `fc` text COLLATE utf8_czech_ci NOT NULL,
  `func` text COLLATE utf8_czech_ci,
  `hold` text COLLATE utf8_czech_ci,
  `res` text COLLATE utf8_czech_ci,
  `profile` text COLLATE utf8_czech_ci,
  `set` text COLLATE utf8_czech_ci,
  `hard` decimal(10,3) NOT NULL DEFAULT '0.000',
  `expand` decimal(10,3) NOT NULL DEFAULT '0.000',
  `collapse` decimal(10,3) NOT NULL DEFAULT '0.000',
  `own` int(11) DEFAULT NULL,
  `in` int(11) DEFAULT NULL,
  `ww` int(11) NOT NULL DEFAULT '1',
  `x` decimal(11,3) NOT NULL DEFAULT '0.000',
  `y` decimal(11,3) NOT NULL DEFAULT '0.000',
  `t` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `own` (`own`),
  KEY `in` (`in`),
  KEY `xy` (`x`,`y`),
  KEY `type` (`type`),
  FULLTEXT KEY `func` (`func`),
  FULLTEXT KEY `res` (`res`),
  FULLTEXT KEY `profile` (`profile`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=10000;
