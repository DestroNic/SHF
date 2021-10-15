<?php
//require_once('lib/c.c.php');
//constants


define("DIRR", "/home/content/s/h/f/shf37/html/beta");
define("URL", "http://www.settlementhousingfund.org/beta");

define("LIB_DIR", DIRR . "/lib");
define("CLASS_DIR", LIB_DIR . "/class");
define("CONSTANTS", LIB_DIR . "/constants.php");

define("CONNECTION", LIB_DIR . "/connect.php");
define("CONFIGURE", LIB_DIR . "/d.c.php");
define("TEMPLATES", DIRR . "/templates");
define("STYLES_URL", URL . "/styles");
define("JS_URL", URL . "/js");
define("CF", LIB_DIR . "/c.f.php");
define("FORM_MAKER" , LIB_DIR . "/form_maker.php");
define("IMAGE_CRAZY", LIB_DIR . "/image.crazy.php");

define("THE_HEADER", TEMPLATES . "/header.tem.php");
define("THE_FOOTER", TEMPLATES . "/footer.tem.php");
define("THE_LEFT", TEMPLATES . "/left.tem.php");

define("ADMIN_DIR", DIRR . "/shf_admin");
define("ADMIN_TEMPLATES", ADMIN_DIR . "/templates");
define("ADMIN_LIB", ADMIN_DIR . "/lib");
define("ADMIN_HEADER", ADMIN_TEMPLATES . "/header.tem.php");
define("ADMIN_FOOTER", ADMIN_TEMPLATES . "/footer.tem.php");

define("LOGIN", ADMIN_DIR . "/lib/login.functions.php");

define("LOGIN_FUNCTIONS", LIB_DIR . "/login.functions.php");
define("ACTIVE", ' class="active"');



if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");
define("EMAIL_ADDRESS", "matt@matthewlittlehale.com");


define("OG_STYLES", URL . "/rrb.css");
define("STYLE_URL", URL . "/styles");
define("IMAGES", URL . "/images");

define("ADMIN_URL", URL . "/shf_admin");
define("ADMIN_STYLES", ADMIN_URL . "/styles/style.css");
define("ADMIN_JS_URL", ADMIN_URL . "/js");

define("FCK_BASEPATH", "/beta/shf_admin/lib/fckeditor/");

$offset = 1;
//categories



?>