<?php
require_once("db_connection.php");

$db = new Database('testhost', 'root', 'option123', 'web_site');

$conn = $db->getConnection();
?>