
# Granby Rotary

## The Rotary Club of Granby Web Site

Granby, Colorado, 80446  
Webmaster: Barton Phillips [bartonphillips@gmail.com](mailto:bartonphillips@gmail.com)  
Website: http://www.granbyrotary.org  
Hosted by: **DigitalOcean.com**  
Copyright &copy; 2017 The Rotary Club of Granby.

This site uses 'GranbyRotary.class.php' which is an extension of SiteClass. The 'composer.json' file has the following code:  

<pre>
{
  "autoload": {  
    "classmap": [  
      "includes"  
    ]  
  }  
}  
</pre>

this causes the entry in ./vendor/composer/autoload_static.php to have the file in the $classMap array.

The files all then start with:  

<pre>
require_once("./vendor/autoload.php");  
$_site = require_once(getenv("SITELOADNAME"));  
$S = new $_site->className($_site);  
</pre>

