-- Rotary database 
-- rssfeeds
use granbyrotarydotorg;
DROP TABLE IF EXISTS rssfeeds;
CREATE TABLE IF NOT EXISTS rssfeeds (
  id int(11) auto_increment not null,
  articleId_fk int(11),
  rsstitle text,
  rsslink text,
  rssdesc text,
  rssdate varchar(50),
  date datetime,
  created datetime,
  expired datetime default '2020-01-01 00:00:00',
  feedorder float(10,6) default 9999.000000,
  lasttime timestamp,
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
