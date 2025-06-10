      <div class="goodList">
          <div class="gdzone">
              <?php
                $maxRows_rs = 12;
                $pageNum_rs = 0;
                if (isset($_GET['pageNum_rs'])) {
                    $pageNum_rs = $_GET['pageNum_rs'];
                }
                $startRow_rs = $pageNum_rs * $maxRows_rs;
                if (isset($_GET['search_name'])) {
                    // 使用關鍵字查詢
                    $queryFirst = sprintf("SELECT * FROM product, product_img, pyclass WHERE p_open = 1 AND product_img.sort = 1 AND product.p_id = product_img.p_id AND product.classid = pyclass.classid AND product.p_name LIKE '%s' ORDER BY product.p_id DESC", '%' . $_GET['search_name'] . '%');
                } elseif (isset($_GET['level']) && $_GET['level'] == 1) {
                    // 使用第一層類別查詢
                    $queryFirst = sprintf("SELECT * FROM product, product_img, pyclass WHERE p_open = 1 AND product_img.sort = 1 AND product.p_id = product_img.p_id AND product.classid = pyclass.classid AND pyclass.uplink = '%d' ORDER BY product.p_id DESC", $_GET['classid']);
                } elseif (isset($_GET['classid'])) {
                    // 使用產品類別查詢
                    $queryFirst = sprintf("SELECT * FROM product, product_img WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid = '%d' ORDER BY product.p_id DESC", $_GET['classid']);
                } else {
                    // 列出產品product資料查詢
                    $queryFirst = sprintf("SELECT * FROM product, product_img WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id ORDER BY product.p_id DESC", $maxRows_rs);
                }

                $query = sprintf("%s LIMIT %d,%d", $queryFirst, $startRow_rs, $maxRows_rs);
                $pList01 = $link->query($query);
                $i = 1; // 控制每列row產生
                if ($pList01->rowCount() != 0) {
                    while ($pList01_Rows = $pList01->fetch()) {
                         ?>
                          <div class="card"><a href="goods.php?p_id=<?php echo $pList01_Rows['p_id']; ?>">
                                  <img id="showGoods" src="./product_img/<?php echo $pList01_Rows['img_file']; ?>" alt="<?php echo $pList01_Rows['p_name']; ?>"></a>
                              <div class="card-not-img">
                                  <h4 class="gdname"><?php echo $pList01_Rows['p_name']; ?></h4>
                                  <div class="card-item">
                                      <p class="gdprice">NT<?php echo $pList01_Rows['p_price']; ?></p>
                                      <button class="addcart">
                                          <i class="fas fa-shopping-cart"></i>
                                      </button>
                                  </div>
                              </div>
                          </div>
                          <?php
                            $i++;
                        }
                                ?>
          </div>
          <div>
              <?php
                    if (isset($_GET['totalRows_rs'])) {
                        $totalRows_rs = $_GET['totalRows_rs'];
                    } else {
                        $all_rs = $link->query($queryFirst);
                        $totalRows_rs = $all_rs->rowCount();
                    }
                    $totalPages_rs = ceil($totalRows_rs / $maxRows_rs) - 1;
                    $prev_rs = "&laquo;";
                    $next_rs = "&raquo;";
                    $separator = "|";
                    $max_links = 20;
                    $pages_rs = buildNavigation($pageNum_rs, $totalPages_rs, $prev_rs, $next_rs, $separator, $max_links, true, 3, "rs");
                ?>
              <nav class="pageNav">
                  <ul>
                      <?php echo $pages_rs[0] . $pages_rs[1] . $pages_rs[2]; ?>
                  </ul>
              </nav>
          </div>
      <?php } else { ?>
          <div class="no-product-holder">
              抱歉，目前沒有相關產品
          </div>
      </div>
      <?php } ?>
      </div>
      