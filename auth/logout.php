<?php
session_start();
session_unset();
session_destroy();

header("Location: /stage/project/index.php");
exit;
