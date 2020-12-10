<?php
include('header.php');
include('config/config.php');

$product_id = $_GET['product_id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $product_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$addtocart_value = $result['id'];

// print_r($_SESSION['cart']);

// print_r("<pre>");
// print_r($result);
// print_r("id=" . $result['id']);
// exit();

?>

<div class="product_image_area" style="padding-top: 0px !important;">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <div class="single-prd-item">
          <img class="img-fluid" src="admin/images/<?php echo escape($result['image']) ?>" alt="" width="500">
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?php echo escape($result['name']); ?></h3>
          <h2><?php echo escape($result['price']); ?></h2>

          <p><?php echo escape($result['description']); ?></p>

          <form action="addtocart.php" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

            <input type="hidden" name="id" value="<?php echo $addtocart_value ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>

            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" href="#" style="border: none">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php'); ?>