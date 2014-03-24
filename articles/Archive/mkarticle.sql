-- Rotary database 
-- articles
use granbyrotarydotorg;
DROP TABLE IF EXISTS articles;
CREATE TABLE IF NOT EXISTS articles (
  id int(11) auto_increment not null,
  name varchar(255),
  pageorder float(10,6) default 9999.000000,
  articletemplate varchar(255),
  rssfeedId_fk int(11),
  rssfeed text,
  article text,
  header text,
  articleInclude  enum('rss', 'article', 'both') default 'both',
  rssInclude  enum('rss', 'article', 'both') default 'rss',
  expired datetime default '2020-01-01 00:00:00',
  expiredMsg text,
  created datetime default NULL,
  lasttime timestamp,
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
