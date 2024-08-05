<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../frontend/app/admin_login.html");
exit();
?>
