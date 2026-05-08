<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/db/mysqli.php');
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$query = $db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = 50");
print_r($query->row['setting']);
?>
