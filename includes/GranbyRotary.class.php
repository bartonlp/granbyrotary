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
    //ErrorClass::setNoEmailErrs(true); // For debugging
    //ErrorClass::setDevelopment(true); // during development

    parent::__construct($x);

    $this->checkId();
    
    // Check to see who can administer our site.

    $this->trackmember();
    
    $n = $this->query("select id from rotarymembers where webadmin='yes'");
    if($n) {
      while(list($id) = $this->fetchrow('num')) {
        $this->admins[] = $id;
      }
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
   * getId()
   * Get user id
   */

  public function getId() {
    return $this->id;
  }

  /**
   * setId()
   */

  public function setId($id) {
    $this->id = $id;
  }

  /**
   * setIdCookie()
   * Sets the browser Cookie to user ID
   * This is used by login logic of this sites.
   */

  public function setIdCookie($id, $cookie=null) {
    $this->id = $id; // This is the table ID from the $membertable.
    if(!$id) return; // If no ID then don't set any cookies

    $expire = time() + 31536000;  // one year from now

    // subDomain is the 'path' in the setcookie function.
    // We raerly use subDomain.
    
    if($this->subDomain) {
      $path = $this->subDomain;
    } else {
      $path = "/";
    }

    $siteid = (is_null($cookie)) ? "SiteId" : $cookie;

    // setSiteCookie() is in SiteClass.
    
    $this->setSiteCookie($siteid, "$id", $expire, $path);
  }

  /**
   * checkId()
   * Check ID info
   * As a side effect sets the $this->grUser, grDistrictId by reading rotarymembers table.
   * @param optional $mid. Member id.
   * @return user's id
   */
  
  public function checkId($mid=null) {
    // For historic reasons we check GrId which was used before I made the site.class.php

    if(isset($mid)) {
      $id = $mid;
    } else {
      $id = $_COOKIE['SiteId']; // New logic in site.class.php uses SiteId
    }
    
    $n = $this->query("select FName, LName, Email, districtId, status, otherclub ".
                      "from rotarymembers where id='$id'");

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
   * feedCount()
   * Count number of accesses to the rss feed.
   * Inserts into database table "feedcnt"
   */
  
  public function feedCount() {
    $agent = $this->agent;
    $this->query("insert into feedcnt (agent, count) values('$agent', 1) on duplicate key update count=count+1");
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

    $this->query("select concat(FName, ' ', LName) as name, visittime " .
    "from rotarymembers where id != 0 and visits != 0 and visittime > current_date() order by visittime desc");

    while($row = $this->fetchrow()) {
      $ret .= "<tr><td>" . stripslashes($row['name']) . "</td><td>{$row['visittime']}</td></tr>\n";
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

  /**
   * trackmember()
   * Track activity on site
   * This table is in the siteName's database.
   * By default this uses the 'logagent' and 'memberpagecnt' tables.
   */

  protected function trackmember() {
    if($this->nodb) {
      return;
    }

    // If there is a member 'id' then update the memberTable

    if($this->id && $this->memberTable) {
      $agent = $this->escape($this->agent);

      $this->query("select count(*) from information_schema.tables ".
                   "where (table_schema = '{$this->dbinfo->database}') and (table_name = '$this->memberTable')");

      list($ok) = $this->fetchrow('num');

      if($ok) {
        // BLP 2016-05-04 -- 
        // The fname-lname are a unique index 'name' so we will not get duplicates of our users.
        
        $sql = "insert into $this->memberTable (fname, lname, email, visits, visittime) ".
               "values('$this->fname', '$this->lname', '$this->email', '1', now()) ".
               "on duplicate key update visits=visits+1, visittime=now()";

        $this->query($sql);
      } else {
        error_log("$this->siteName: $this->self: table $this->memberTable does not exist in the {$this->dbinfo->database} database");
      }
      
      // BLP 2014-09-16 -- add nomemberpagecnt

      if(!$this->nomemberpagecnt) {
        $this->query("select count(*) from information_schema.tables ".
                     "where (table_schema = '{$this->dbinfo->database}') and (table_name = 'memberpagecnt')");

        list($ok) = $this->fetchrow('num');

        if($ok) {
          $sql = "insert into memberpagecnt (page, id, ip, agent, count, lasttime) " .
                 "values('$this->requestUri', '$this->id', '$this->ip', '$agent', '1', now()) ".
                 "on duplicate key update count=count+1, ip='$this->ip', agent='$agent', lasttime=now()";

          $this->query($sql);
        } else {
          error_log("$this->siteName: $this->self: table memberpagecnt does not exist in the {$this->dbinfo->database} database");
        }
      }
    }
  }
  
  public function __toString() {
    return __CLASS__;
  }
} // End of class GranbyRotary
