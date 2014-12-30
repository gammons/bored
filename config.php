<?php

/* DO NOT CHANGE THIS FILE

This file is part of the Debian package for phpbb. To configure
phpbb3, use dbconfig-common (dpkg-reconfigure phpbb3) or for a manual
override or multiple boards, see below.

For using multiple phpbb boards on this computer with different databases,
include 'php_value auto_prepend_file /etc/phpbb3/alternative_config.php'
in the corresponding <Location> or <VirtualHost> section of your webserver's config.
That file will then override the standard config. For an example file, see
/usr/share/doc/phpbb3/examples/config.php. See also /usr/share/doc/phpbb3/README.multiboard.

*/

if (!defined('PHPBB_INSTALLED')) {
    // database.inc.php also exists when the question if
    // dbconfig-common should be used was answered with "No"
    // therefore we don't need to check its existence
    @include('database.inc.php');
    if (empty($dbtype) && @constant('IN_INSTALL')!='true') {
        die("The board configuration seems to be incomplete. Use the
             dbconfig-common method provided with the package setup
             (<tt>dpkg-reconfigure phpbb3</tt>) to have a database
             setup automatically, or see
             <tt>/usr/share/doc/phpbb3/README.Debian</tt> and/or
             <tt>/usr/share/doc/phpbb3/README.multiboard</tt> for
             information on how to configure manually.");
    }
    // now translate the dbconfig-common variables to the phpBB internal vars
    $dbms = ($dbtype == 'pgsql' ? 'postgres' : $dbtype);
    if ($dbtype == 'sqlite') {
        $dbhost = "$basepath/$dbname";
    } else {
        $dbhost = $dbserver;
    }
    $dbpasswd = $dbpass;
    if (!isset($table_prefix)) {
        $table_prefix = 'phpbb_';
    }
    
    $load_extensions = '';
    
    define('PHPBB_INSTALLED', true);
}
if (!isset($acm_type)) {
    // don't break multi-sites from < 3.0.7-PL1-1
    $acm_type = 'file';
}

// $url_forum is used inside the patched phpBB code in Debian to
// ensure a working multisite cache/store/...
// Use the database name as a default fallback.
if (empty($url_forum)) {
	$url_forum = $dbname;
}
