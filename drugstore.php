<?php
require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php")
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once("head.php"); ?>
</head>

<body>
  <?php require_once("navbar.php"); ?>
  <!-- <ul class="breadcrumb">
    <li><a href="#">首頁</a></li>
    <li>全部商品</li>
  </ul> -->
  <?php require_once("breadcrumb.php") ?>
  <div class="content">
    <?php require_once("sidebar.php"); ?>
    <div class="not-sidebar">
      <div class="gdpresent">
        <div class="gdtotal">共x項商品</div>
        <div class="gdsort"><select name="gdsort" id="gdsort">
            <option value="decrease">價格高到低</option>
            <option value="increase">價格低到高</option>
          </select></div>
      </div>
      <?php require_once("goodList.php"); ?>
    </div>
  </div>
  <footer>
    <div class="small-words">
      <p class="small-left">&copy;2025 by Ohyah Good</p>
      <p class="small-right">本站最佳瀏覽環境請使用Google Chrome、Firefox或Edge以上版本</p>
    </div>
  </footer>
  <script src="index.js"></script>
</body>

</html>