<?php
   // !!!!!!!!!!!!!!!!! For 1and1 ONLY !!!!!!!!!!!!!!!!!!!!!!
   // Auto load Classes
   // Finds the .sitemap.php for a site. Starts at the lowest level and works up until we reach a
   // .sitemap.php or the DOC_ROOT without finding anything.
   // If success then reads the .sitemap.php to get the layout of the site and the database and
   // site info for Database and SiteClass. This info is usually used by a site specific subclass
   // that enharits from SiteClass and instantiates a Database if needed.
   // Once the site layout is know the class autoloader uses that to load Classes.
   
// Define the document root


// NOTE: conditionally defined functions must be defined BEFORE they are used. This is unlike
// unconditionally defined function which can be defined after being called in the source!

//***************
// recursive function to find the site map file. $n is the depth we should search up from where we
// are.

if(!function_exists('findSiteMap')) {
  function findSiteMap($siteFile, $n) {
    // Does the file exist here?
    if(file_exists($siteFile)) {
      return "$siteFile";
    } else {
      // NO. Have we searched all the way up to the doc root yet?
      if($n-- == 0) {
        return null;
      }
      // No we have not so hop up a directory level and try again.

      $siteFile = "../$siteFile";
      return findSiteMap($siteFile, $n);
    }
  }
}

//***********************
// Set up the Auto Loader
// Auto load function for database and SiteClass or includes for sites
// Look in all the possible locations

if(!function_exists('siteAutoLoad')) {
  function siteAutoLoad($class) {
    // echo "class: $class<br>";
    // Look in the top level includes dir.
    $file = INCLUDES . "/$class.class.php";
    if(!file_exists($file)) {
      // The new database engines look like 'dbMysqli.class.php' etc. and are in the database engines
      // directory (usually 'includes/database-engines/' under TOP.
      $file = DATABASE_ENGINES . "/$class.class.php";
      //echo "database engines: $file<br>";
      if(!file_exists($file)) {
        // Site specific classes 
        $file = SITE_INCLUDES . "/$class.class.php";
        //echo "site includes: $file<br>";
        if(!file_exists($file)) {
          // Try everything again with all lower case
          $file = $clLower = strtolower($class) . ".class.php"; // include path search
          if(!file_exists($file)) {
            // Look in the top includes dir
            $file = INCLUDES . "/$clLower";
            if(!file_exists($file)) {
              // in the site includes dir
              $file = SITE_INCLUES . "/$clLower";
              if(!file_exists($file)) {
                // Now get the file name of the file that is trying to instantiate the calss
                $x = debug_backtrace();
                // get the path info
                $dir = dirname($x[1]['file']);
                $file = "{$dir}/$clLower";
                if(!file_exists($file)) {
                  // last try look to see if the directory of the file that is trying to instantiate
                  // the class has an includes directory and try that
                  $file = "{$dir}/includes/$clLower";
                  //echo "file=$file<br>";
                  if(!file_exists($file)) {
                    // Failed miserably!
                    throw new Exception("Class Auto Loader could not fine class $class");
                  }
                }
              }
            }
          }
        }
      }
    }

    // Could have been a long trip but we finally have the class file we were looking for.
    include_once($file);
  }
}

define('DOC_ROOT', $_SERVER['VIRTUALHOST_DOCUMENT_ROOT']);

// Get the directory path for our file, that is, the file that included this file
// Use PHP_SELF's basename and find the absolute path on the server and capture the dirname part.

$siteautoloader_myDir = dirname(realpath(basename($_SERVER['PHP_SELF'])));

// Now count the number of '/' in the doc root and myDir. This tells us how far our file is down from
// the doc root. We will search up the tree only that far.

$a = substr_count(DOC_ROOT, '/');
$b = substr_count($siteautoloader_myDir, '/');

// n is the depth down from the root

$n = $b - $a;

// The file we are looking for in the dir tree

$siteFile = ".sitemap.php";
$x = findSiteMap($siteFile, $n);

// were we succcessful?

if($x) {
  // define SITE_ROOT for the .sitemap.ph file to use.
  
  define('SITE_ROOT', dirname(realpath($x)));

  // Finally what we have come here for we include the .sitemap.php file that has the site
  // configuration information.
  
  include($x);
} else {
  echo "Failed to Load .sitemap.php<br>";
  echo "DOC_ROOT: " . DOC_ROOT . "<br>siteautoloader_myDir: $siteautoloader_myDir<br>";
  echo "b(myDir)=$b<br>a(DOC_ROOT)=$a<br>n=$n<br>";
  echo "x=$x<br>" . "realpath of x: " . dirname(realpath($x)) . "<br>";
  // Failure
  throw new Exception("Did not find '.sitemap.php' before " . DOC_ROOT);
}

// Grab the helper function as soon as we know where they are. They should always be with the
// database stuff

require_once(DATABASE_ENGINES . "/helper-functions.php");

// Register autoload functon

spl_autoload_register('siteAutoLoad');
?>