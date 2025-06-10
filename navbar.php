<header>
 <div id="header">
    <div class="logo"><a href="index.php"><img src="images/minami_icon.png" alt="" style="height: 100px;"></a></div>
    <div class="nav">
      <?php
      global $link;
      $SQLstring = "SELECT * FROM pyclass WHERE level=1 ORDER BY sort";
      $pyclass01 = $link->query($SQLstring);
      while ($pyclass01_Rows = $pyclass01->fetch()) {
      ?>
        <div class="dropdown">
          <div class="dropbtn">
            <?php echo $pyclass01_Rows['cname']; ?>
          </div>
          <ul class="dropdown-content">
            <?php
            $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_Rows['classid']);
            $pyclass02 = $link->query($SQLstring);
            while ($pyclass02_Rows = $pyclass02->fetch()) {
            ?>
              <li><a href="drugstore.php?classid=<?php echo $pyclass02_Rows['classid']; ?>"><?php echo $pyclass02_Rows['cname']; ?></a></li><?php } ?>
          </ul>
        </div>
      <?php
      }
  
      ?>
    </div>
    <div class="item">
      <div class="icons">
        <div class="member-system">
          <a><i class="far fa-user fa-lg"></i>
          </a>
          <ul class="member-choice">
            <?php if (isset($_SESSION["login"])) { ?>
            <img src="uploads/<?php echo ($_SESSION["imgname"]) != "" ? $_SESSION["imgname"] : "一粒.PNG"; ?>" width="40" height="40">
            <?php } ?>
            <li><a href="register.php">會員註冊</a></li>
            <li><a href="login.php">會員登入</a></li>
            <li><a href="orderlist.php">我的訂單</a></li>
              <?php if (isset($_SESSION["login"])) { ?>
            <li><a href="#" onclick="btn_confirmLink('請確定是否要登出', 'logout.php');">登出</a></li>
            <?php } ?>
          </ul>
        </div>
        <div class="shopping-cart"><a href="cart.php"><i class="fas fa-shopping-cart fa-lg"></i></a></div>
      </div>
  
      <form class="search-bar" name="search" id="search" action="drugstore.php" method="get">
        <input type="text" name="search_name" id="search_name" placeholder="search">
      </form>
    </div>
 </div>
</header>