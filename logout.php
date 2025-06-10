<?php
!isset($_SESSION) ? session_start() : "";

$_SESSION['login'] = null;
$_SESSION['emailid'] = null;
$_SESSION['email'] = null;
$_SESSION['cname'] = null;
$_SESSION['imgname'] = null;
unset($_SESSION['login']);
unset($_SESSION['emailid']);
unset($_SESSION['email']);
unset($_SESSION['cname']);
unset($_SESSION['imgname']);
$sPath = "index.php";
// header(sprintf("Location: %s", $sPath));
// php 5.2.6舊版採用下列方式
echo sprintf("<script>window.location.href='%s';</script>", $sPath);
?>