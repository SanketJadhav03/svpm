<?php
include "../config/connection.php";

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $principal_id = $_GET['id'];

    // Query to fetch the principal's details for deleting the photo file (if any)
    $query = "SELECT principal_photo FROM tbl_principal WHERE principal_id = '$principal_id'";
    $result = mysqli_query($conn, $query);
    $principal = mysqli_fetch_assoc($result);

    // Check if the principal exists
    if ($principal) {
        // Delete the principal's photo file from the server if it exists and is not the default image
        if ($principal['principal_photo'] != 'default.png') {
            $photo_path = "../assets/images/principal/" . $principal['principal_photo'];
            if (file_exists($photo_path)) {
                unlink($photo_path);
            }
        }

        // Query to delete the principal from the database
        $delete_query = "DELETE FROM tbl_principal WHERE principal_id = '$principal_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Principal deleted successfully!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error deleting principal.'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Principal not found.'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location='index.php';</script>";
}
?>
