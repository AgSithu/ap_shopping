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

$id = $_GET['id'];

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
        if (empty($_POST['name'])) {
            $nameError = 'Name is required';
        }
        if (empty($_POST['description'])) {
            $descError = 'Description is required';
        }
    } else {
        
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE categories SET name='$name',description='$description' WHERE id='$id'");
        $result = $stmt->execute();

        if ($result) {
            echo "<script>alert('Category Edited');window.location.href='category.php';</script>";
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
                        <?php
                        $id = $_GET['id'];
                        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=" . $id);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        // print_r("<pre>");
                        // print_r($result);
                        // print_r($result[0]['name']);
                        // print_r($result[0]['description']);
                        // die();

                        ?>
                        <form action="#" method="post" enctype="multipart/form-data">
                            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <label for="">Name</label>
                                <p style="color: red;"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" value=<?php echo escape($result[0]['name']); ?> class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <p style="color: red;"><?php echo empty($descError) ? '' : '*' . $descError; ?></p>
                                <textarea class="form-control" name="description" cols="80" rows="8" required><?php echo escape($result[0]['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT">
                                <a href="category.php" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'footer.html';
?>