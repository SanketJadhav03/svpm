<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="font-weight-bold">Principal Management</h3>
                <a href="create.php" class="btn btn-success">+ Add Principal</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Principal Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        $query = "SELECT * FROM `tbl_principal`";
                        $result = mysqli_query($conn, $query);
                        
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?= ++$count ?></td>
                                <td>
                                    <img src="<?= $base_url ?>assets/images/principal/<?= $data["principal_photo"] ?: "default.png" ?>" height="50" width="50" alt="Profile">
                                </td>
                                <td><?= $data["principal_name"] ?></td>
                                <td><?= $data["principal_email"] ?></td>
                                <td><?= $data["principal_phone"] ?></td>
                                <td><?= $data["principal_address"] ?></td>
                                <td>
                                    <a href="view.php?principal_id=<?= $data["principal_id"] ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="edit.php?principal_id=<?= $data["principal_id"] ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="delete.php?principal_id=<?= $data["principal_id"] ?>" onclick="return confirm('Are you sure you want to delete this Principal?');" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        if ($count == 0) {
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">No Principals Found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "../component/footer.php"; ?>
