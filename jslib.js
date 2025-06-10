// 將產品p_id加入購物車
function addcart(p_id) {
    let qty = $("#qty").val();
    if (qty <= 0) {
        alert("產品數量不得為0或為負數，請再修改數量!");
        return false;
    }
    if (qty == undefined) {
        qty = 1;
    } else if (qty >= 50) {
        alert("由於採購限制，產品數量將限制在50以下!");
        return false;
    }
    // 利用jquery $.ajax函數呼叫後台的addcart.php
    $.ajax({
        url: 'addcart.php',
        type: 'get',
        dataType: 'json',
        data: { p_id: p_id, qty: qty, },
        success: function (data) {
            if (data.c === "1") {
                alert(data.m);
                window.location.reload();
            } else {
                alert(data.m);
            }
        },
        error: function (data) {
            alert("系統目前無法連接到後台資料庫。");
        }
    });
}

// 跳出確認訊息對話框
function btn_confirmLink(message, url) {
    if (message === "" || url === "") {
        return false;
    }
    if (confirm(message)) {
        window.location = url;
    }
    return false;
}