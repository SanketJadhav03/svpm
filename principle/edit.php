<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";

// Get the principal's ID from the URL parameter
if (isset($_GET['principal_id'])) {
    $principal_id = $_GET['principal_id'];

    // Query to get the principal details by ID
    $query = "SELECT * FROM tbl_principal WHERE principal_id = '$principal_id'";
    $result = mysqli_query($conn, $query);
    $principal = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Principal not found.'); window.location='index.php';</script>";
    exit;
}

// Handle form submission for updating the principal details
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
        $principal_photo = $principal['principal_photo']; // Retain the old photo if no new photo is uploaded
    }

    // Update query to edit the principal's details
    $query = "UPDATE tbl_principal SET 
                principal_name = '$principal_name', 
                principal_email = '$principal_email', 
                principal_password = '$hashed_password', 
                principal_phone = '$principal_phone', 
                principal_address = '$principal_address', 
                principal_photo = '$principal_photo' 
              WHERE principal_id = '$principal_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Principal updated successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error updating principal.');</script>";
    }
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <h3>Edit Principal Details</h3>
        </div>
        <div class="card-body">
            <form class="row" method="POST" enctype="multipart/form-data">
                <div class="form-group col-4">
                    <label for="principal_name">Name</label>
                    <input type="text" class="form-control" id="principal_name" name="principal_name" value="<?php echo $principal['principal_name']; ?>" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_email">Email</label>
                    <input type="email" class="form-control" id="principal_email" name="principal_email" value="<?php echo $principal['principal_email']; ?>" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_password">Password</label>
                    <input type="password" class="form-control" id="principal_password" name="principal_password" value="<?php echo $principal['principal_password']; ?>" required>
                </div>
                <div class="form-group col-4">
                    <label for="principal_phone">Phone</label>
                    <input type="text" class="form-control" id="principal_phone" name="principal_phone" value="<?php echo $principal['principal_phone']; ?>" required>
                    <label for="principal_photo" class="mt-5">Profile Photo</label>
                    <input type="file" class="form-control-file" id="principal_photo" name="principal_photo">
                    <br>
                    <?php if ($principal['principal_photo'] != "default.png"): ?>
                        <img src="../assets/images/principal/<?php echo $principal['principal_photo']; ?>" alt="Principal Photo" width="100">
                    <?php endif; ?>
                </div>
                <div class="form-group col-8">
                    <label for="principal_address">Address</label>
                    <textarea class="form-control" id="principal_address" name="principal_address" rows="6"><?php echo $principal['principal_address']; ?></textarea>
                </div>
                <div class="text-center mt-3 col-12">
                    <button type="submit" class="btn btn-primary">Update Principal</button>
                    <a href="index.php" class="btn btn-success"> <i class="fa fa-arrow-left"></i> Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>