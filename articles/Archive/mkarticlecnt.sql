-- Rotary database 
-- articlecnt
use granbyrotarydotorg;
DROP TABLE IF EXISTS articlecnt ;
CREATE TABLE IF NOT EXISTS articlecnt (
  id int(11) not null,
  memberId int(11),
  count int(11),
  lasttime timestamp,
  PRIMARY KEY  (id, memberId)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
