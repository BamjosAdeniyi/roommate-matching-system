<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../frontend/app/admin/admin_login.html");
exit();
?>
