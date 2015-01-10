<?php
/* REMEMBER function names are case-insensitive!!!! */
/**
 * GranbyRotary Class
 *
 * This class provides methods for www.granbyrotary.org
 * This classs extends the Site Class which extendds the Database Class.
 * @see site.class.php and db.class.php
 * @package GranbyRotary
 * @author Barton Phillips <barton@bartonphillips.com>
 * @version 1.0
 * @link http://www.bartonphillips.com
 * @copyright Copyright (c) 2010, Barton Phillips
 * @license http://opensource.org/licenses/gpl-3.0.html GPL Version 3
 */

// BE CAREFUL this could change!!!
define(ID_BARTON, 25);  // define my database id

/**
 * @package GranbyRotary extends SiteClass which extends Database
 */

class GranbyRotary extends SiteClass {
  private $admins = array(); // array of admins  
  private $grUser;  // "$FName $LName" as one unit from database, or empty
  private $grOtherclub;
  
  /**
   * Constructor
   * new GranbyRotary()
   * Connects database, does page count optionally.
   * @param boolean $count Default true. If true do the counter logic else don't
   */
  
  public function __construct($x=array()) {
    global $dbinfo, $siteinfo; // from .sitemap.php

    $s = $siteinfo;
    $s['databaseClass'] = new Database($dbinfo);

    // If $x has values then add/modify the $s array

    if(!is_null($x)) foreach($x as $k=>$v) {
      $s[$k] = $v;
    }

    parent::__construct($s); // NOTE: parent constructor calls checkId() which uses this classes method not the parents!

    // Check to see who can administer our site.
    
    $n = $this->query("select id from rotarymembers where webadmin='yes'");
    if($n) {
      while($row = $this->fetchrow()) {
        $this->admins[] = $row[0];
      }
    }
  }

  /**
   * getBanner()
   * Get the banner
   * Extends base class. Provides the rotary wheel image, a <h1>heading, and $pageTitle.
   * @param string $pageTitle Default blank
   * @param bool $nonav don't show navbar
   * @param string $bodytag
   * @return string
   */
  
  public function getBanner($pageTitle = "", $nonav=null, $bodytag=null)
  {
    /*
    $mainTitle = "<img src='/images/wheel.gif' ".
                 "title='Granby Rotary Club' alt='Rotary Wheel'/>\n<h1>The Rotary Club of Granby</h1>\n";
    */
    $ret = parent::getBanner("$mainTitle\n$pageTitle", $nonav, $bodytag);
    return $ret;
  }

  /**
   * getFooter()
   * Get Page Footer
   * Extends base class. No google analitics, and DNT message if isDNT() set.
   * @param variable number of args.
   *  arguments can be strings (defaults: $msg1='', $msg2='', $ctrmsg=''),
   *  an assoc array, or an object.
   *  for array and object the elements are 'msg1', 'msg2', 'ctrmsg''
   * @return string
   */

  public function getFooter($b=null) {
    if(is_string($b)) {
      $x->msg1 = $b;
    } else {
      $x = (object)$b; // Force $b to an object
    }
    
    return parent::getFooter($x);
  }

  /**
   * daycount()
   * Day Counts
   * Override base class
   */
  
  protected function daycount() {
    $blpIp = $this->myIp;

    if($this->ip != $blpIp && $this->id != ID_BARTON) {
      // The bots table is updated from our sites only (2013-11-13). It use to be updated from the
      // virtual access log for the ISP but they stopped letting me look at those logs.
      // It still has good information however.
      
      $n = $this->query("select ip from bots where ip='$this->ip'");

      if($n) {
        // BOT
        $this->query("insert into daycounts (date, count, robotcnt, visits, ip, id) " .
                     "values(now(), 1, 1, 0, '$this->ip', '$this->id') " .
                     "on duplicate key update count=count+1, robotcnt=robotcnt+1");
      } else {
        // NOT BOT
        $this->query("insert into daycounts (date, count, visits, ip, id) " .
                     "values(now(), 1, 0, '$this->ip', '$this->id') " .
                     "on duplicate key update count=count+1, id='$this->id'");
      }

      if($this->id) {
        $this->query("update daycounts set members=members+1, id='$this->id' ".
                     "where ip='$this->ip' and date=now()");
      }
        
      if(!($cookietime = $_COOKIE['blptime'])) {
        $cookietime = time();
        // set cookie to expire in 10 minutes
        setcookie("blptime", $cookietime, $cookietime + (60*10), "/", "granbyrotary.org", false, true);
        $this->query("update daycounts set visits=visits+1, id='$this->id' ".
                     "where ip='$this->ip' and date=now()");
      }

      // BLP Nov 10, 2013 add counter2
      // CREATE TABLE `counter2` (
      //   `date` date NOT NULL DEFAULT '0000-00-00',
      //   `filename` varchar(255) NOT NULL DEFAULT '',
      //   `count` int(11) DEFAULT NULL,
      //   `members` int(11) Default null,
      //   `bots` int(11) default null,
      //   `lasttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      //   PRIMARY KEY (`date`,`filename`)
      // ) ENGINE=MyISAM DEFAULT CHARSET=utf8

      $member = $this->id ? ", members=members+1" : "";
      $bot = $n ? ", bots=bots+1" : "";
    
      $sql = "insert into counter2 (date, filename, count, members, bots) ".
             "values(date(now()), '$this->self', 1, " . ($this->id ? 1 : 0) .
             ", " . $n .
             ") on duplicate key update count=count+1{$member}{$bot}";

      $this->query($sql);
    }
  }
    
  /**
   * getUser()
   * Get the user name
   */
  
  public function getUser() {
    return $this->grUser;
  }

  /**
   * setUser()
   * Set the user's name
   */
  
  public function setUser($user) {
    $this->grUser = $user;
  }

  /**
   * checkId()
   * Check ID info
   * Override base class!
   * This is called by the base class constructor.
   * As a side effect sets the $this->grUser, grDistrictId by reading rotarymembers table.
   * @return user's id
   */
  
  public function checkId() {
    // For historic reasons we check GrId which was used before I made the site.class.php
    
    $id = $_COOKIE['GrId'];
    
    if(!isset($id)) {
      $id = $_COOKIE['SiteId']; // New logic in site.class.php uses SiteId
    }
    
    $n = $this->query("select FName, LName, Email, districtId, status, otherclub from rotarymembers where id='$id'");

    if(!$n) {
      // OPS DIDN'T FIND THE ID IN THE DATABASE?
      return 0;
    }

    $row = $this->fetchrow('assoc');
    extract($row);
    // We must map this class and parent class properties!
    
    $this->grUser = "$FName $LName";
    $this->grOtherclub = $otherclub;

    // These are from parent site.class.php
    
    $this->fname = $FName;
    $this->lname = $LName;
    $this->email = $Email;
    $this->id = $id;
    return $id;
  }

  /**
   * isMember()
   */
  
  public function isMember() {
    if(!isset($this->grOtherclub)) {
      return false;
    } else {
      return $this->grOtherclub == 'none' ? false : true;
    }
  }
  
  /**
   * checkBBoard()
   * Check BBoard
   * @return blank or number of new bulletin board entries ("<br/>$cnt New Post" . ($cnt == 1 ? "" : "s")
   */
  
  public function checkBBoard() {
    $msg = "";

    $id = $this->getId();

    if($id) {
      $this->query("select count(item) from bboard");  // count all items in bboard

      $row = $this->fetchrow();

      $bbcount = $row[0]; // total items in bb

      $this->query("select count(item) from bbsreadmsg where id='$id'"); // now count the number of items that I have read

      $row = $this->fetchrow();

      $bbsreadcnt = $row[0]; // items that I have read

      // If there are some items in the bb

      if($bbcount) {
        // subtract the total from what I have read, this is the number
        // of UN read items.

        $cnt = $bbcount - $bbsreadcnt;

        // If ther are any unread items

        if($cnt) {
          $msg = "<br/>$cnt New Post" . ($cnt == 1 ? "" : "s");
        }
      }
    }
    return $msg;
  } 

  /**
   * feedCount()
   * Count number of accesses to the rss feed.
   * Inserts into database table "feedcnt"
   */
  
  public function feedCount() {
    $agent = $this->agent;
    $this->query("insert into feedcnt (agent, count) values('$agent', 1) on duplicate key update count=count+1");
  }
  
  /**
   * newsChanged()
   * Check if the News Page has changed since user looked last
   * @return true or false
   */
  
  public function newsChanged() {
    $d = $this->getLastmod();
    $this->query("select lastnews from rotarymembers where id=$this->id");

    $row = $this->fetchrow();
    $lastnews = $row['lastnews'];

    //echo "d=$d, lastnews=$lastnews<br>";
    //echo "d=" . strtotime($d) . " lastnews=" . strtotime($lastnews) . "<br>";

    if(strtotime($d) > strtotime($lastnews)) {
      return true;
    }
    return false;
  }

  /**
   * lookedAtNews()
   * Set "lastnews" field in "rottarymembers" every time News Page viewed
   */
  
  public function lookedAtNews() {
    // update members lastnews
    date_default_timezone_set('America/Denver');
    $date = date("Y-m-d H:i:s");
    $this->query("update rotarymembers set lastnews='$date' where id='$this->id'");
  }

  /**
   * loginInfo()
   * Login Info Functioin
   * This is only used by the login.php
   * when members first login.
   */
  
  public function loginInfo() {
    $agent = $this->agent;
    $ip = $this->ip;

    // save IP, ID info

    $this->query("insert into logip (ip, count, id) values('$ip', '1', '$this->id')
    on duplicate key update count=count+1, id=$this->id");

    // save IP, AGENT, ID info

    $this->query("insert into logagent (ip, agent, count, id) values('$ip', '$agent', '1', '$this->id')
    on duplicate key update count=count+1, id='$this->id'");

    // Now update the users visit counter

    $this->query("update rotarymembers set visits=visits+1, visittime=now() where id='$this->id'");
  }

  /**
   * getLastmod()
   * @access private
   */
  
  private function getLastmod() {
    $this->query("select max(lasttime) as date from articles");
    $row = $this->fetchrow();
    return $row['date'];    
  }

  /**
   * getWhosBeenHereToday()
   * define here as we need different table semantics
   * Override base class
   */
  
  public function getWhosBeenHereToday() {
//    ob_start();
    $ret =<<<EOF
<table class='who' border="1">
<thead>
<tr>
<th>Who's visited our Website today?</th>
<th>Last Time</th>
</tr>
</thead>
<tbody>
EOF;

  // NOTE the database last field has the San Diego time not our
  // time. So use ADDTIME to add one hour to the time to get Mountain
  // time.

  $this->query("select concat(FName, ' ', LName) as name, " .
    "date_format(addtime(visittime, '1:0'), '%H:%i:%s') as last " .
    "from rotarymembers where id != 0 and visits != 0 and visittime > current_date() order by visittime desc");

  while($row = $this->fetchrow()) {
    $ret .= "<tr><td>" . stripslashes($row['name']) . "</td><td>$row[last]</td></tr>\n";
  }

  $ret .=<<<EOF
</tbody>
</table>

EOF;
    return $ret;
  }

  /**
   * isAdmin()
   * @return bool
   */

  public function isAdmin($id) {
    return (in_array($id, $this->admins));
  }

  /**
   * adminText()
   * @return string
   */

  public function adminText() {
    return <<<EOF
<div style="text-align: center;">
<form action="admintext.php" method="post">
<input type="submit" value="Administration Features" style="background-color: pink;" />
<input type="hidden" name="key" value="41144blp"/>
<input type="hidden" name="sender" value="$this->self"/>
</form>
</div>
EOF;
  }

  public function __toString() {
    return __CLASS__;
  }
} // End of class GranbyRotary

// ********************************************************************************

// Callback to get the user id for db.class.php SqlError

if(!function_exists(ErrorGetId)) {
  function ErrorGetId() {
    $id = $_COOKIE['GrId'];
    if(empty($id)) {
      $id = $_COOKIE['SiteId'];
    }
    
    if(empty($id)) {
      $id = "IP={$_SERVER['REMOTE_ADDR']}, AGENT={$_SERVER['HTTP_USER_AGENT']}";
    }
    return $id;
  }
}

// WARNING THERE MUST BE NOTHING AFTER THE CLOSING PHP TAG.
// Really nothing not even a space!!!!
?>