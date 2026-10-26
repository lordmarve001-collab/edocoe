<?php
session_start();
session_destroy();
session_unset();
header("location:https://portal.edocohest.edu.ng/");

?>