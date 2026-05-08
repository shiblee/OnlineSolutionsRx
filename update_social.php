<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/db/mysqli.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

$facebook = 'https://www.facebook.com/onlinesolutionsrxofficial';
$instagram = 'https://www.instagram.com/onlinesolutionsrx1/';

// Get current setting
$query = $db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = 50");
if ($query->num_rows) {
    $setting = $query->row['setting'];
    
    // Perform replacements
    $setting = str_replace('https://www.facebook.com/profile.php?id=100087213459452', $facebook, $setting);
    $setting = str_replace('https://www.instagram.com/onlinesolutionsrx/', $instagram, $setting);
    
    // Update
    $db->query("UPDATE " . DB_PREFIX . "module SET setting = '" . $db->escape($setting) . "' WHERE module_id = 50");
    echo "Social URLs updated successfully!";
} else {
    echo "Module 50 not found!";
}
?>
