<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $principal_name = mysqli_real_escape_string($conn, $_POST['principal_name']);
    $principal_email = mysqli_real_escape_string($conn, $_POST['principal_email']);
    $principal_password = mysqli_real_escape_string($conn, $_POST['principal_password']);
    $principal_phone = mysqli_real_escape_string($conn, $_POST['principal_phone']);
    $principal_address = mysqli_real_escape_string($conn, $_POST['principal_address']);
    $principal_photo = $_FILES['principal_photo']['name'];
    $photo_temp = $_FILES['principal_photo']['tmp_name'];

    // Password hashing
    $hashed_password = password_hash($principal_password, PASSWORD_DEFAULT);

    if ($principal_photo) {
        $target_dir = "../assets/images/principal/";
        $target_file = $target_dir . basename($principal_photo);
        move_uploaded_file($photo_temp, $target_file);
    } else {
        $principal_photo = "default.png";
    }

    $query = "INSERT INTO tbl_principal (principal_name, principal_email, principal_password, principal_phone, principal_address, principal_photo) 
              VALUES ('$principal_name', '$principal_email', '$hashed_password', '$principal_phone', '$principal_address', '$principal_photo')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Principal added successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error adding principal.');</script>";
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="align-items-center d-flex justify-content-between">
                <div class="h5 font-weight-bold">Add Principal</div>
                <a href="index.php" class="btn btn-info shadow font-weight-bold">
                    <i class="fa fa-eye"></i>&nbsp; Principals List
                </a>

            </div>
        </div>
        <div class="card-body">
            <form class="row" method="POST" enctype="multipart/form-data">
                <div class="form-group col-4">
                    <label for="principal_name">Name</label>
                    <input type="text" class="form-control" id="principal_name" name="principal_name" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_email">Email</label>
                    <input type="email" class="form-control" id="principal_email" name="principal_email" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_password">Password</label>
                    <input type="password" class="form-control" id="principal_password" name="principal_password" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_phone">Phone</label>
                    <input type="text" class="form-control" id="principal_phone" name="principal_phone" required>
                    <label for="principal_photo" class="mt-5">Profile Photo</label>
                    <input type="file" class="form-control-file" id="principal_photo" name="principal_photo">
                </div>
                <div class="form-group col-8">
                    <label for="principal_address">Address</label>
                    <textarea class="form-control" id="principal_address" name="principal_address" rows="6"></textarea>
                </div>
                <div class="col-12">
                <hr class="font-weight-bold">
                </div>
                <div class="d-flex justify-content-center col-12">
                    <button type="submit" class="btn btn-success shadow"> <i class="fa fa-save"></i> Save</button>
                    <button type="reset" class="btn btn-danger shadow ml-2"> <i class="fa fa-times"></i> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>