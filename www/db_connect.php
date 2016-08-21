<?php
define("HOST", "localhost"); // The host you want to connect to.
define("USER", "neo"); // The database username.
define("PASSWORD", "admin1234"); // The database password.
define("DATABASE", "secure_login"); // The database name.
//phpinfo();
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
// If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
?>
