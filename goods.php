<?php
require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <?php require_once("head.php"); ?>
  <style>
    .goods-card {
      display: flex;
    }
    .ecTitle {
      font-weight: bold;
      font-size: 20px;
    }
    .ecTable {
      border: 1px solid #999999;
      width: 100%;
      border-collapse: collapse;
    }
    .td1, .td2 {
      width: 50%;
      border: 1px solid #999999;
    }
  </style>
</head>

<body>
  <?php require_once("navbar.php"); ?>

  <?php require_once("breadcrumb.php") ?>

  <div class="content">
    <?php require_once("sidebar.php"); ?>
    <div class="not-sidebar">
      <div class="goods-card">
        <?php
        // 取得產品圖片檔名資料
        $SQLstring = sprintf("SELECT * FROM product_img WHERE product_img.p_id = %d ORDER BY sort", $_GET['p_id']);
        $img_rs = $link->query($SQLstring);
        $imgList = $img_rs->fetch();
        ?>
        <img id="showGoods" name="showGoods" src="product_img/<?php echo $imgList['img_file']; ?>" alt="<?php echo $data['p_name']; ?>" title="<?php echo $data['p_name']; ?>">
        <div class="goods-message">
          
          <h3><?php echo $data['p_name']; ?></h3>
          <p><?php echo $data['p_intro']; ?></p>
          <h4>$<?php echo $data['p_price']; ?></h4>
          <div>
            
              
                <span class="input-group-text color-success" id="inputGroup-sizing-lg">數量</span>
                <input type="number" id="qty" name="qty" value="1">
              
            
            
              <button name="button01" id="button01" type="button" onclick="addcart(<?php echo $data['p_id']; ?>)">加入購物車</button>
            
          </div>
        </div>
      </div>
      <?php echo $data['p_content']; ?>
    </div>
  </div>
  <?php require_once("footer.php"); ?>
  <?php require_once("jsfile.php"); ?>
</body>

</html>