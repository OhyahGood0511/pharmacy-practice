<?php
require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <?php require_once("head.php");?>
</head>

<body>
  <?php require_once("navbar.php"); ?>

  <?php require_once("breadcrumb.php") ?>
  <div class="content">
    <?php require_once("sidebar.php"); ?>
    <div class="not-sidebar">
      <div class="gdpresent">
        <div class="gdtotal">共x項商品</div>
        <div class="gdsort">
          <select name="gdsort" id="gdsort">
            <option value="decrease">價格高到低</option>
            <option value="increase">價格低到高</option>
          </select>
        </div>
      </div>
    <?php require_once("goodList.php"); ?>
    </div>
  </div>
  <?php require_once("footer.php"); ?>
  <?php require_once("jsfile.php"); ?>
</body>

</html>