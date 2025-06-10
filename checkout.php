<?php
require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php");
if (!isset($_SESSION['login'])) {
    $sPath = "login.php?sPath=checkout";
    header(sprintf("Location: %s", $sPath));
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <?php require_once("head.php"); ?>
    <style>
        /* * {
            border: 1px solid black;
        } */
        .information {
            display: grid;
            /* height: 100%; */
            grid-template-columns: 1fr 1fr;
        }

        .receiver {
            background-color: pink;
        }

        .payment {
            background-color: red;
        }

        .merchant-list {
            background-color: aqua;
            grid-column: 1 / -1;
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
            // 取得收件者地址資料
            $SQLstring = sprintf("SELECT *,city.Name AS ctName, town.Name AS toName FROM addbook, city, town WHERE emailid='%d' AND setdefault='1' AND addbook.myzip=town.Post AND town.AutoNo=city.AutoNo", $_SESSION['emailid']);
            $addbook_rs = $link->query($SQLstring);
            if ($addbook_rs && $addbook_rs->rowCount() != 0) {
                $data = $addbook_rs->fetch();
                $cname = $data['cname'];
                $mobile = $data['mobile'];
                $myzip = $data['myzip'];
                $address = $data['address'];
                $ctName = $data['ctName'];
                $toName = $data['toName'];
            } else {
                $cname = "";
                $mobile = "";
                $myzip = "";
                $address = "";
                $ctName = "";
                $toName = "";
            }
            ?>
            <h1>結帳作業</h1>
            <div class="information">
                <div class="receiver">
                    <h3>配送資訊</h3>
                    <p>收件人姓名: <?php echo $cname; ?></p>
                    <p>電話: <?php echo $mobile; ?></p>
                    <p>郵遞區號: <?php echo $myzip . $ctName . $toName; ?></p>
                    <p>地址: <?php echo $address; ?></p>
                    <button onclick="openDialog()">選擇其他收件人</button>
                    <?php
                    // 收得所有收件人資料
                    $SQLstring = sprintf("SELECT *, city.Name AS ctName, town.Name AS toName FROM addbook, city, town WHERE emailid='%d' AND addbook.myzip = town.Post AND town.AutoNo = city.AutoNo", $_SESSION['emailid']);
                    $addbook_rs = $link->query($SQLstring);
                    ?>
                    <dialog class="others">
                        <form action="">
                            <input type="text" name="cname" id="cname" placeholder="姓名">
                            <input type="text" name="mobile" id="mobile" placeholder="收件人電話">
                            <select name="myCity" id="myCity">
                                <option value="">請選擇市區</option>
                                <?php
                                $city = "SELECT * FROM city WHERE state = 0";
                                $city_rs = $link->query($city);
                                while ($city_rows = $city_rs->fetch()) {
                                ?>
                                    <option value="<?php echo $city_rows['AutoNo']; ?>"><?php echo $city_rows['Name']; ?></option>
                                <?php } ?>
                            </select>
                            <select name="myTown" id="myTown">
                                <option value="">請選擇地區</option>
                            </select>
                            <input type="hidden" name="myZip" id="myZip" value="">
                            <label for="address">郵遞區號:</label>
                            <input type="text" id="address" name="address">
                            <button type="button" id="recipient" name="recipient">新增收件人</button>
                        </form>
                    </dialog>
                </div>
                <div class="payment"></div>
                <div class="merchant-list"></div>
            </div>
        </div>
    </div>
    <?php require_once("footer.php"); ?>
    <?php require_once("jsfile.php"); ?>
    <script>
        $(function() {
            // 取得縣市代碼後查鄉鎮市的名稱
            $("#myCity").change(function() {
                let CNo = $('#myCity').val();
                if (CNo == "") {
                    return false;
                }
                $('#myZip').val("");
                $('#add_label').html("郵遞區號:");
                $.ajax({ // 將鄉鎮市的名稱從後台資料庫取回
                    url: 'Town_ajax.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        CNo: CNo,
                    },
                    success: function(data) {
                        if (data.c == true) {
                            $("#myTown").html(data.m);
                            $("#myZip").val("");
                        } else {
                            alert(data.m);
                        }
                    },
                    error: function(data) {
                        alert("系統目前無法連接到後台資料庫");
                    }
                });
            });
            // 取得鄉鎮市代碼，查詢郵遞區號放入#myZip, #zipcode
            $("#myTown").change(function() {
                let AutoNo = $("#myTown").val();
                if (AutoNo == "") {
                    $("#myZip").val("");
                    $("#add_label").html("");
                    return false;
                }
                $.ajax({
                    url: 'Zip_ajax.php',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        AutoNo: AutoNo,
                    },
                    success: function(data) {
                        if (data.c == true) {
                            $("#myZip").val(data.Post);
                            $("#add_label").html('郵遞區號:' + data.Post + data.Cityname + data.Name);
                        } else {
                            alert(data.m);
                        }
                    },
                    error: function(data) {
                        alert("系統目前無法連接到後台資料庫");
                    }
                });
            });
        });
        // 系統進行結帳處理
        $("#btn04").click(function() {
            let msg = "系統將進行結帳處理，請確認產品金額與收件人是否正確!";
            if (!confirm(msg)) return false;
            $("#loading").show();
            let addressid = $("input[name=gridRadios]:checked").val();
            $.ajax({
                url: "addorder.php",
                type: 'post',
                dataType: 'json',
                data: {
                    addressid: addressid,
                },
                success: function(data) {
                    if (data.c == true) {
                        alert(data.m);
                        window.location.href = "index.php";
                    } else {
                        alert("Database response error:" + data.m);
                    }
                },
                error: function(data) {
                    alert("ajax request error");
                }

            });
        });

        const dialog = document.querySelector(".others");
        function openDialog() {
            dialog.showModal();
        }
        dialog.addEventListener("click", (e) => {
            if (e.target === dialog)
                dialog.close();
        })
    </script>

</body>

</html>