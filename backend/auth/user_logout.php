<?php
session_start();
session_destroy();
header("Location: ../../frontend/app/user/user_login_form.php");
exit();
?>