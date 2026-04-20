<?php
session_start();
unset($_SESSION['client_ID']);
header('location:index.php');
?>