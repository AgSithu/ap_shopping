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
                        <h3 class="card-title">Order Listing</h3>
                    </div>

                    <?php
                    if (!empty($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    $numberOfrecs = 3;
                    $offset = ($pageno - 1) * $numberOfrecs; // starting point to fetch from database

                    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();

                    $total_pages = ceil(count($rawResult) / $numberOfrecs);

                    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numberOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    ?>

                    <div class="card-body">
                        <div>
                            <a href="cat_add.php" type="button" class="btn btn-success">New Category</a>
                        </div><br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>User</th>
                                    <th>Total Price</th>
                                    <th>Order Date</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if ($result) {
                                    $i = 1;
                                    foreach ($result as $value) {
                                ?>

                                        <?php
                                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['user_id']);
                                        $userStmt->execute();
                                        $userResult = $userStmt->fetchAll();
                                        ?>

                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($userResult[0]['name']) ?></td>
                                            <td><?php echo escape($value['total_price']) ?></td>
                                            <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <div class="container">
                                                        <a href="order_detail.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-success">View</a>
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