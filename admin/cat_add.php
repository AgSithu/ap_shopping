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

if ($_POST) 
{
    if (empty($_POST['name']) || empty($_POST['description'])) 
    {
        if (empty($_POST['name'])) 
        {
            $nameError = 'Name is required';
        }
        if (empty($_POST['description'])) {
            $descError = 'Description is required';
        }
    } else 
    {
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("INSERT INTO categories(name, description) VALUES(:name,:description)");
        $result = $stmt->execute(
            array(
                'name' => $name,
                'description' => $description,
            )
        );

        if ($result) 
        {
            echo "<script>alert('Category Added');window.location.href='category.php';</script>";
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
                        <form action="cat_add.php" method="post" enctype="multipart/form-data">
                            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <label for="">Name</label>
                                <p style="color: red;"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <p style="color: red;"><?php echo empty($descError) ? '' : '*' . $descError; ?></p>
                                <textarea class="form-control" name="description" cols="80" rows="8" required></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="SUBMIT">
                                <a href="category.php" class="btn btn-warning">Back</a>
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