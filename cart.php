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
        table {
            border-collapse: collapse;
            text-align: center;
        }

        td {
            border: 1px solid #999999;
        }

        table img {
            width: 200px;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php require_once("navbar.php"); ?>

    <?php require_once("breadcrumb.php") ?>
    <div class="content">
        <?php require_once("sidebar.php"); ?>
        <div class="not-sidebar">

            <?php
            // 建立購物車資料查詢
            $SQLstring = "SELECT * FROM cart, product, product_img WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "' AND orderid IS NULL AND cart.p_id = product_img.p_id AND cart.p_id = product.p_id AND product_img.sort = 1 ORDER BY cartid DESC";
            $cart_rs = $link->query($SQLstring);
            $ptotal = 0; // 設定累加的變數，初始為0
            ?>
            <h3>電商藥妝: 購物車</h3>
            <?php if ($cart_rs->rowCount() != 0) { ?>

                <div>
                    <table>
                        <thead>
                            <tr>
                                <td width="10%">產品編號</td>
                                <td width="10%">圖片</td>
                                <td width="25%">名稱</td>
                                <td width="15%">價格</td>
                                <td width="10%">數量</td>
                                <td width="15%">小計</td>
                                <td width="15%">下次再買</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($cart_data = $cart_rs->fetch()) { ?>
                                <tr>
                                    <td><?php echo $cart_data['p_id']; ?></td>
                                    <td><img src="product_img/<?php echo $cart_data['img_file']; ?>" alt="<?php echo $cart_data['p_name']; ?>" class="img-fluid"></td>
                                    <td><?php echo $cart_data['p_name']; ?></td>
                                    <td>
                                        <h4><?php echo $cart_data['p_price']; ?></h4>
                                    </td>
                                    <td style="min-width: 100px;">
                                        <div>
                                            <input type="number" id="qty[]" name="qty[]" value="<?php echo $cart_data['qty']; ?>" min="1" max="49" cartid="<?php echo $cart_data['cartid']; ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <h4><?php echo $cart_data['p_price'] * $cart_data['qty']; ?></h4>
                                    </td>
                                    <td><button type="button" id="btn[]" name="btn[]" onclick="btn_confirmLink('確定刪除本資料?', 'shopcart_del.php?mode=1&cartid=<?php echo $cart_data['cartid']; ?>');">刪除</button></td>
                                </tr>
                            <?php $ptotal += $cart_data['p_price'] * $cart_data['qty'];
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">累計: <?php echo $ptotal; ?></td>
                            </tr>
                            <tr>
                                <td colspan="7">運費: 100</td>
                            </tr>
                            <tr>
                                <td colspan="7">總計: <?php echo $ptotal + 100; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- <a href="index.php" id="btn01" name="btn01">繼續購物</a>
                    <button type="button" id="btn02" name="btn02" onclick="window.history.go(-1)">回到上一頁</button> -->
                    <button type="button" id="btn03" name="btn03" onclick="btn_confirmLink('確定清空購物車?', 'shopcart_del.php?mode=2');">清空購物車</button>
                    <a href="checkout.php">前往結帳</a>
                </div>
            <?php } else { ?>
                <div role="alert" style="text-align: center;">抱歉!目前購物車沒有相關產品。</div>
            <?php } ?>

        </div>
    </div>
    <?php require_once("footer.php"); ?>
    <?php require_once("jsfile.php"); ?>
    <script>
        // 將變更的數量寫入後台資料庫
        $("input").change(function() {
            let qty = $(this).val();
            const cartid = $(this).attr("cartid");
            if (qty <= 0 || qty >= 50) {
                alert("更改數量需大於0以上，以及小於50以下。");
                return false;
            }
            $.ajax({
                url: 'change_qty.php',
                type: 'post',
                dataType: 'json',
                data: {
                    cartid: cartid,
                    qty: qty,
                },
                success: function(data) {
                    if (data.c == true) {
                        // alert(data.m);
                        window.location.reload();
                    } else {
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("系統目前無法連接到後台資料庫");
                }
            });
        })
    </script>
</body>

</html>