    <?php
    $SQLstring = "SELECT * FROM pyclass WHERE level = 1 ORDER BY sort";
    $pyclass01 = $link->query($SQLstring);
    ?>
    <div class="sidebar">
        <?php
        while ($pyclass01_Rows = $pyclass01->fetch()) {
            $i = $pyclass01_Rows['classid'];
        ?>
            <button class="accordion"><?php echo $pyclass01_Rows['cname']; ?></button>
            <?php
            if (isset($_GET['p_id'])) { // 如果使用產品查詢，需取得類別編號上一層類別
                $SQLstring = sprintf("SELECT uplink FROM pyclass, product WHERE pyclass.classid = product.classid AND p_id = %d", $_GET['p_id']);
                $classid_rs = $link->query($SQLstring);
                $sideData = $classid_rs->fetch();
                $ladder = $sideData['uplink'];
            } elseif (isset($_GET['level']) && $_GET['level'] == 1) { // 使用第一層類別查詢
                $ladder = $_GET['classid'];
            } elseif (isset($_GET['classid'])) { // 如果使用類別查詢需取得上一層類別
                $SQLstring = "SELECT uplink FROM pyclass WHERE level=2 AND classid=" . $_GET['classid'];
                $classid_rs = $link->query($SQLstring);
                $sideData = $classid_rs->fetch();
                $ladder = $sideData['uplink'];
            } else {
                $ladder = 1;
            }
            ?>
            <?php
            // 列出產品類別對應的第二層資料
            $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_Rows['classid']);
            $pyclass02 = $link->query($SQLstring);
            ?>
            <div class="panel">
                <?php while ($pyclass02_Rows = $pyclass02->fetch()) { ?>
                    <a href="drugstore.php?classid=<?php echo $pyclass02_Rows['classid']; ?>" class="accordion-content"><?php echo $pyclass02_Rows['cname']; ?></a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>