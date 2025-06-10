<?php

require_once("Connections/conn_db.php");
!isset($_SESSION) ? session_start() : "";
require_once("php_lib.php")
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once("head.php"); ?>
  <style>
    h1 {
      text-align: center;
    }
    #reg {
      margin: 0 auto;
      border: 1px solid #999999;
      padding: 0 30px 20px 30px;
    }
    #reg input {
      display: block;
      margin-bottom: 10px;
      width: 100%;
      
    }
    
  </style>
</head>

<body>
  <?php require_once("navbar.php"); ?>
 
  <div class="content">
    <?php require_once("sidebar.php"); ?>
    <?php
    if (isset($_POST['formct1']) && $_POST['formct1'] == 'reg') {
      $email = $_POST['email'];
      $pw1 = md5($_POST['pw1']);
      $cname = $_POST['cname'];
      $tssn = $_POST['tssn'];
      $birthday = $_POST['birthday'];
      $mobile = $_POST['mobile'];
      $myzip = $_POST['myZip'] == '' ? NULL : $_POST['myZip'];
      $address = $_POST['address'] == '' ? NULL : $_POST['address'];
      $imgname = $_POST['uploadname'] == '' ? NULL : $_POST['uploadname'];
      $insertsql = "INSERT INTO member (email, pw1, cname, tssn, birthday, imgname) VALUES ('" . $email . "', '" . $pw1 . "', '" . $cname . "', '" . $tssn . "', '" . $birthday . "', '" . $imgname . "')";
      
      $Result = $link->query($insertsql);
      $emailid = $link->lastInsertId(); // 讀剛新增會員編號
      if ($Result) {
        // 將會員的姓名、電話、地址寫入addbook
        $insertsql = "INSERT INTO addbook (emailid, setdefault, cname, mobile, myzip, address) VALUES ('" . $emailid . "', '1', '" . $cname . "', '" . $mobile . "', '" . $myzip . "', '" . $address . "')";
        $Result = $link->query($insertsql);
        $_SESSION['login'] = true; // 設定會員註冊完直接登入
        $_SESSION['emailid'] = $emailid;
        $_SESSION['email'] = $email;
        $_SESSION['cname'] = $cname;
        $_SESSION['imgname'] = $imgname;
        echo "<script>alert('謝謝您，會員資料已完成註冊');location.href='index.php';</script>";
      }
    }
    ?>
    
    <form action="register.php" method="POST" name="reg" id="reg">
       <h1>會員註冊</h1>
      <fieldset>
        <legend>帳號密碼</legend>
        <input type="email" name="email" id="email" placeholder="*請輸入email帳號" autocomplete="off">
        <input type="password" name="pw1" id="pw1" placeholder="*請輸入密碼">

        <input type="password" name="pw2" id="pw2" placeholder="*請再次確認密碼">
      </fieldset>
      <fieldset>
        <legend>個人基本資料</legend>
        <input type="text" name="cname" id="cname" placeholder="*請輸入姓名">


        <input type="text" name="tssn" id="tssn" placeholder="請輸入身分證字號">


        <input type="text" name="birthday" id="birthday" onfocus="(this.type='date')" placeholder="*請選擇生日">


        <input type="text" name="mobile" id="mobile" placeholder="*請輸入手機號碼">


        <select name="myCity" id="myCity">
          <option value="">請選擇市區</option>
          <?php $city = "SELECT * FROM city WHERE State=0";
          $city_rs = $link->query($city);
          while ($city_rows = $city_rs->fetch()) {
          ?>
            <option value="<?php echo $city_rows['AutoNo']; ?>"><?php echo $city_rows['Name']; ?></option>
          <?php } ?>
        </select><br>
        <select name="myTown" id="myTown">
          <option value="">請選擇地區</option>
        </select>

        <label for="address" id="zipcode" name="zipcode">郵遞區號:地址</label>

        <input type="hidden" name="myZip" id="myZip" value="">
        <input type="text" name="address" id="address" placeholder="請輸入後續地址">

        <label for="fileToUpload">上傳相片:</label>
        <div>
          <input type="file" name="fileToUpload" id="fileToUpload" title="請上傳相片圖示" accept="image/x-png, image/jpeg, image/gif, image/jpg">
          <p><button type="button" class="submitImg" id="uploadForm" name="uploadForm">開始上傳</button></p>
          <div id="progress-div01" class="progress" style="width: 100%;display:none;">
            <div id="progress-bar01" class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">0%</div>
          </div>
          <input type="hidden" name="uploadname" id="uploadname" value="">
          <img id="showing" name="showing" src="" alt="photo" style="display: none;">
        </div>
      </fieldset>
      <div>
        <input type="hidden" name="captcha" id="captcha" value="">
        <a href="javascript:void(0);" title="按我更新認證碼" onclick="getCaptcha();">
          <canvas id="can"></canvas>
        </a>
        <input type="text" name="recaptcha" id="recaptcha" placeholder="請輸入認證碼">
      </div>
      <input type="hidden" name="formct1" id="formct1" value="reg">
      <div><button type="submit">送出</button></div>
    </form>

  </div>
  <?php require_once("footer.php"); ?>

  <?php require_once("jsfile.php"); ?>
  <script src="commlib.js"></script>
  <script src="jquery.validate.js"></script>
  <script>
    // 自訂身分證格式驗證
    jQuery.validator.addMethod("tssn", function(value, element, param) {
      let tssn = /^[a-zA-Z]{1}[0-9]{8}$/;
      return this.optional(element) || tssn.test(value);
    });
    // 自訂手機格式驗證
    jQuery.validator.addMethod("checkphone", function(value, element, param) {
      let checkphone = /^[0]{1}[9]{1}[0-9]{8}$/;
      return this.optional(element) || checkphone.test(value);
    });
    // 自訂郵遞區號驗證
    jQuery.validator.addMethod("checkMyTown", function(value, element, param) {
      return value !== "";
    });
    // 驗證form #reg表單
    $("#reg").validate({
      rules: {
        email: {
          required: true,
          email: true,
          remote: 'checkemail.php'
        },
        pw1: {
          required: true,
          maxlength: 20,
          minlength: 4
        },
        pw2: {
          required: true,
          equalTo: '#pw1'
        },
        cname: {
          required: true
        },
        tssn: {
          required: false,
          tssn: true
        },
        birthday: {
          required: true
        },
        mobile: {
          required: true,
          checkphone: true
        },
        address: {
          required: true
        },
        myTown: {
          checkMyTown: true
        },
        recaptcha: {
          required: true,
          equalTo: '#captcha'
        },
      },
      messages: {
        email: {
          required: 'email信箱不得為空白',
          email: 'email信箱格式有誤',
          remote: 'email信箱已經註冊'
        },
        pw1: {
          required: '密碼不得為空白!!',
          maxlength: '密碼最大長度為20位(4-20位英文字母與數字的組合)',
          minlength: '密碼最小長度為4位(4-20位英文字母與數字的組合)'
        },
        pw2: {
          required: "確認密碼不得為空白!!",
          equalTo: '兩次輸入的密碼必須一致!!'
        },
        cname: {
          required: '使用者名稱不得為空白!!'
        },
        tssn: {
          required: '使用者身份證不得為空白!!',
          tssn: '身份證ID格式有誤'
        },
        birthday: {
          required: '生日不得為空白!!'
        },
        mobile: {
          required: '手機號碼不得為空白!!',
          checkphone: '手機號碼格式有誤'
        },
        address: {
          required: '地址不得為空白!!'
        },
        myTown: {
          checkMyTown: '需選擇郵遞區號'
        },
        recaptcha: {
          required: '驗證碼不得為空白！!',
          equalTo: '驗證碼需相同！!'
        },
      },
    });
    // 取得元素id
    function getId(el) {
      return document.getElementById(el);
    }
    // 圖示上傳處理
    $("#uploadForm").click(function(e) {
      let fileName = $("#fileToUpload").val();
      let idxDot = fileName.lastIndexOf(".") + 1;
      let extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
      if (extFile == "jpg" || extFile == "jpeg" || extFile == "png" || extFile == "gif") {
        $("#progress-div01").css("display", "flex");
        let file1 = getId("fileToUpload").files[0];
        let formdata = new FormData();
        formdata.append("file1", file1);
        let ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "file_upload_parser.php");
        ajax.send(formdata);
        return false;
      } else {
        alert("目前只支援jpg, jpeg, png, gif檔案格式上傳!");
      }
    });
    // 上傳過程顯示百分比
    function progressHandler(event) {
      let percent = Math.round((event.loaded / event.total) * 100);
      $("#progress-bar01").css("width", percent + "%");
      $("#progress-bar01").html(percent + "%");
    }
    // 上傳完成處理顯示圖片
    function completeHandler(event) {
      let data = JSON.parse(event.target.responseText);
      if (data.success == "true") {
        $("#uploadname").val(data.fileName);
        $("#showing").attr({
          'src': 'uploads/' + data.fileName,
          'style': 'display:block;'
        });
        $("button.submitImg").attr({
          'style': 'display:none;'
        });
      } else {
        alert(data.error);
      }
    }
    // Upload Failed: 上傳發生錯誤處理
    function errorHandler(event) {
      alert("Upload Failed: 上傳發生錯誤");
    }
    // Upload Aborted: 上傳作業取消處理
    function abortHandler(event) {
      alert("Upload Aborted: 上傳作業取消");
    }

    function getCaptcha() {
      let inputTxt = document.getElementById("captcha");
      // can為canvas的id名稱
      // 150為影像寬，50為影像高，blue為影像背景顏色
      // white為文字顏色，28px為文字大小，5為認證碼長度
      inputTxt.value = captchaCode("can", 150, 50, "blue", "white", "28px", 5);
    }
    $(function() {
      // 啟動認證碼功能
      getCaptcha();
      // 取得縣市代碼後查鄉鎮市的名稱
      $("#myCity").change(function() {
        let CNo = $('#myCity').val();
        if (CNo == "") {
          return false;
        }
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
              $("#zipcode").html(data.Post + data.Cityname + data.Name);
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