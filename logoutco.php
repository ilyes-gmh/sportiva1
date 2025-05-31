<?php
session_start();
session_destroy();
/* Back to login (change path if needed) */
header('Location: home.php');
exit;
