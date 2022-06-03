<?php
session_start();
unset($_SESSION['sip']);
session_destroy();

header("Location:../login.php");
