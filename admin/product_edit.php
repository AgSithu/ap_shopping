<?php

session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("Location: login.php");
}

if ($_SESSION['role'] == 0) {
    header("Location: login.php");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) ||
        empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])
    ) {
        if (empty($_POST['name'])) {
            $nameError = 'Product Name is required';
        }
        if (empty($_POST['description'])) {
            $descError = 'Product Description is required';
        }
        if (empty($_POST['category'])) {
            $catError = 'Category is required';
        }
        if (empty($_POST['quantity'])) {
            $qtyError = 'Quantity is required';
        }
        if (empty($_POST['price'])) {
            $priceError = 'Price is required';
        }
        if (empty($_FILES['image'])) {
            $imgError = 'Image is required';
        }
    } elseif ($_POST['quantity'] && (is_numeric($_POST['quantity']) != true)) {
        $qtyError = "Quantity should be integer value";
    } elseif ($_POST['price'] && (is_numeric($_POST['price']) != 1)) {
        $priceError = "Price should be integer value";
    } else {
        //validation success
        $id = $_POST['id'];
        if ($_FILES['image']['name'] != null) {

            $file = 'images/' . ($_FILES['image']['name']);
            $imageType = pathinfo($file, PATHINFO_EXTENSION);

            if ($imageType != 'jpg' && $imageType != 'png' && $imageType != 'jpeg') {
                echo "<script>alert('Image should be jpg, jpeg or png');</script>";
            } else {
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $category = $_POST['category'];
                $qty = $_POST['quantity'];
                $price = $_POST['price'];
                $image = $_FILES['image']['name'];               

                move_uploaded_file($_FILES['image']['tmp_name'], $file);

                $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity,image=:image 
                                       WHERE id=:id");

                $result = $stmt->execute(
                    array(':name' => $name, ':description' => $desc, ':category' => $category, ':price' => $price, ':quantity' => $qty, ':image' => $image, ':id' => $id)
                );
                if ($result) {
                    echo "<script>alert('Product is updated');window.location.href='index.php';</script>";
                }
            }
        } else {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $category = $_POST['category'];
            $qty = $_POST['quantity'];
            $price = $_POST['price'];

            $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,price=:price,quantity=:quantity 
                                       WHERE id=:id");

            $result = $stmt->execute(
                array(':name' => $name, ':description' => $desc, ':category' => $category, ':price' => $price, ':quantity' => $qty, ':id' => $id)
            );

            if ($result) {
                echo "<script>alert('Product is updated');window.location.href='index.php';</script>";
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <label for="">Name</label>
                                <p style="color: red;"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" value=<?php echo escape($result[0]['name']) ?>>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <p style="color: red;"><?php echo empty($descError) ? '' : '*' . $descError; ?></p>
                                <textarea class="form-control" name="description" cols="80" rows="8"><?php echo escape($result[0]['description']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <?php
                                $catStmt = $pdo->prepare("SELECT * FROM categories");
                                $catStmt->execute();
                                $catResult = $catStmt->fetchAll();
                                // print_r("<pre>");
                                // print_r($catResult); exit();
                                // 
                                ?>
                                <label for="">Category Id</label>
                                <p style="color: red;"><?php echo empty($catError) ? '' : '*' . $catError; ?></p>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php foreach ($catResult as $value) { ?>
                                        <?php if ($value['id'] == $result[0]['category_id']) : ?>
                                            <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                        <?php endif ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <p style="color: red;"><?php echo empty($qtyError) ? '' : '*' . $qtyError; ?></p>
                                <input type="number" class="form-control" name="quantity" value=<?php echo escape($result[0]['quantity']) ?>>
                            </div>
                            <div class="form-group">
                                <label for="">Price</label>
                                <p style="color: red;"><?php echo empty($priceError) ? '' : '*' . $priceError; ?></p>
                                <input type="number" class="form-control" name="price" value=<?php echo escape($result[0]['price']) ?>>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label>
                                <p style="color: red;"><?php echo empty($imgError) ? '' : '*' . $imgError; ?></p>
                                <img src="images/<?php echo escape($result[0]['image']) ?>" alt="" width="150" height="150"><br>
                                <input type="file" name="image">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT">
                                <a href="index.php" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->


                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php
require 'footer.html';
?>