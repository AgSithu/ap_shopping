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

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['phone'])) {
        if (empty($_POST['name'])) {
            $nameError = 'Name cannot be null';
        }
        if (empty($_POST['email'])) {
            $emailError = 'Email cannot be null';
        }
        if (empty($_POST['address'])) {
            $addressError = 'Address cannot be null';
        }
        if (empty($_POST['phone'])) {
            $phoneError = 'Phone cannot be null';
        }
    } elseif (empty($_POST['password']) && strlen($_POST['password']) < 4) {
        $passwordError = 'Password should be 4 characters at least';
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        if (empty($_POST['role'])) {
            $role = 0;
        } else {
            $role = 1;
        }

        // check duplicate email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
        $stmt->execute(array(':email' => $email, ':id' => $id));
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<script>alert('Email duplicated')</script>";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', address='$address', phone='$phone', role='$role' WHERE id='$id'");
            $result = $stmt->execute();
            echo "<script>alert('Successfully Uploaded');window.location.href='user_list.php';</script>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
// print_r("<pre>"); print_r($result);exit();
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
                            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">

                                <label for="">Name</label>
                                <p style="color:red"><?php echo empty($nameError) ? '' : '*' . $nameError; ?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <p style="color:red"><?php echo empty($emailError) ? '' : '*' . $emailError; ?></p>
                                <textarea class="form-control" name="email" cols="80" rows="8"><?php echo escape($result[0]['email']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <p style="color:red"><?php echo empty($passwordError) ? '' : '*' . $passwordError; ?></p>
                                <textarea class="form-control" name="password" cols="80" rows="8"><?php echo escape($result[0]['password']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Address</label>
                                <p style="color:red"><?php echo empty($addressError) ? '' : '*' . $addressError; ?></p>
                                <textarea class="form-control" name="address" cols="80" rows="8" ><?php echo escape($result[0]['address']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <p style="color:red"><?php echo empty($phoneError) ? '' : '*' . $phoneError; ?></p>
                                <input type="number" class="form-control" name="phone" value="<?php echo escape($result[0]['phone']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Role</label><br>
                                <input type="checkbox" name="role" id="role" value="1" <?php echo $result[0]['role'] == 1 ? 'checked' : '' ?>>
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