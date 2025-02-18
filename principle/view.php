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

    // Check if the principal exists
    if (!$principal) {
        echo "<script>alert('Principal not found.'); window.location='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request.'); window.location='index.php';</script>";
    exit;
}
?>

<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <h3>Principal Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td><?php echo $principal['principal_name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $principal['principal_email']; ?></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>********</td> <!-- Don't display the actual password -->
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $principal['principal_phone']; ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo nl2br($principal['principal_address']); ?></td>
                </tr>
                <tr>
                    <th>Profile Photo</th>
                    <td>
                        <?php if ($principal['principal_photo'] != "default.png"): ?>
                            <img src="../assets/images/principal/<?php echo $principal['principal_photo']; ?>" alt="Principal Photo" width="150">
                        <?php else: ?>
                            <img src="../assets/images/principal/default.png" alt="Default Photo" width="150">
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo date("d/m/Y H:i", strtotime($principal['created_at'])); ?></td>
                </tr>
            </table>
            <div class="text-center mt-3">
            <a href="index.php" class="btn btn-success"> <i class="fa fa-arrow-left"></i> Back to List</a>
            </div>
        </div>
    </div>
</div>

<?php include "../component/footer.php"; ?>
