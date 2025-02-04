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

if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
    if (empty($_GET['pageno'])) {
        unset($_COOKIE['search']);
        setcookie('search', null, -1, '/');
    }
}

?>

<?php
include 'header.php';
?>


<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Category List</h3>
                    </div>

                    <?php
                    if (!empty($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    $numberOfrecs = 3;
                    $offset = ($pageno - 1) * $numberOfrecs; // starting point to fetch from database

                    if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult) / $numberOfrecs);

                        $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numberOfrecs");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                    } else {
                        $searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
                        $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();
                        $total_pages = ceil(count($rawResult) / $numberOfrecs);

                        $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numberOfrecs");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                    }

                    ?>

                    <div class="card-body">
                        <div>
                            <a href="cat_add.php" type="button" class="btn btn-success">New Category</a>
                        </div><br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) {
                                ?>

                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($value['name']) ?></td>
                                            <td><?php echo escape(substr($value['description'], 0, 100)) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <div class="container">
                                                        <a href="cat_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                                                    </div>
                                                    <div class="container">
                                                        <a href="cat_delete.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Are you sure want to delete this item')" type="button" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                <?php
                                        $i++;
                                    }
                                }
                                ?>

                            </tbody>
                        </table><br>
                        <nav aria-label="Page navigation example" style="float: right">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                <li class="page-item <?php if ($pageno <= 1) echo 'disable'; ?>">
                                    <a class="page-link" href="<?php if ($pageno <= 1) echo "#";
                                                                else echo "?pageno=" . ($pageno - 1); ?>">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                                <li class="page-item <?php if ($pageno >= $total_pages) echo 'disable'; ?>">
                                    <a class="page-link" href="<?php if ($pageno >= $total_pages) echo '#';
                                                                else echo "?pageno=" . ($pageno + 1); ?>">Next</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php
require 'footer.html';
?>