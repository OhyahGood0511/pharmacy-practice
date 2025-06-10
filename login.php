<?php
require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php");
if (isset($_GET['sPath'])) {
    $sPath = $_GET['sPath'] . ".php";
} else {
    $sPath = "index.php";
}
if (isset($_SESSION['login'])) {
    header(sprintf("location: %s", $sPath));
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <?php require_once("head.php");?>
  <style>
    #form1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #999999;
        text-align: center;
        width: 500px;
        height: 550px;
        border-radius: 10px;
        margin: 0 auto 20px;
    }

    #form1 h1{
        margin-top: 80px;
    }

    #form1 input {
        height: 40px;
        width: 90%;
    }

    #inputAccount {
        margin-top: 80px;
    }

    #inputPassword {
        margin-top: 20px;
        margin-bottom: 60px;
    }

    #form1 button {
        width: 90px;
        height: 45px;
        font-size:20px;
    }

    .login-zone {
        height: 600px;
        text-align: center;
    }

    .login-zone a {
        text-decoration: none;
        color: #000;
    }
    .login-zone a:hover {
        font-weight: bold;
    }

  </style>
</head>

<body>
  <?php require_once("navbar.php"); ?>
  <div class="content">
    <?php require_once("sidebar.php"); ?>
    <div class="not-sidebar">
      
          <div class="login-zone">
              <form action="" method="POST" id="form1">
                <h1>會員登入</h1>
                <input type="email" id="inputAccount" name="inputAccount" placeholder="Account" required autofocus />
            <input type="password" id="inputPassword" name="inputPassword" placeholder="Password" required />
            <button type="submit">sign in</button>
            </form>
            <div>
                <a href="register.php">會員註冊</a>
            </div>
          </div>
     
    
    </div>
  </div>
  <?php require_once("footer.php"); ?>
  <?php require_once("jsfile.php"); ?>
  <script src="commlib.js"></script>
  <script>
    $(function() {
        $("#form1").submit(function() {
            const inputAccount = $("#inputAccount").val();
            const inputPassword = MD5($("#inputPassword").val());
            $.ajax({
                url: "auth_user.php",
                type: "post",
                dataType: "json",
                data: {
                    inputAccount: inputAccount,
                    inputPassword: inputPassword,
                },
                success: function(data) {
                    if (data.c == true) {
                        alert(data.m);
                        window.location.href = "<?php echo $sPath; ?>"
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
  </script>
</body>

</html>