<?php
session_start();
session_destroy();
header("Location: ../../frontend/app/user/login.php");
exit();
?>