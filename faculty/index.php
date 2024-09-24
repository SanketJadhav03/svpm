<?php
include "../component/header.php";
include "../component/sidebar.php";
?>
<div class="content-wrapper p-2">
    <div class="card ">
        <div class="card-header">
            <div class="text-center p-3">
            <h3 class="font-weight-bold">Faculty</h3>
            </div>
            <div class="row justify-content-end">
           
            <div class="col-2 font-weight-bold" >
                By Name 
                <input type="text" class="form-control font-weight-bold" placeholder="Name">
            </div>
            <div class="col-1 font-weight-bold" >
            <br>    
            <button type="button" class="shadow btn w-100 btn-info font-weight-bold">Search</button>
            </div>
            <div class="col-1 font-weight-bold" >
            <br>    
            <button type="button" class="shadow btn w-100 font-weight-bold btn-primary">Export</button>
            </div>
            <div class="col-1 font-weight-bold" >
                 <br>
                <button type="button" class="shadow btn w-100 font-weight-bold btn-danger">PDF</button>
            </div>
            <div class="col-2 font-weight-bold" >
                 <br>
                <a href="create.php" class="font-weight-bold shadow btn w-100 btn-success">+ Add Faculty</a>
            </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table ">
                    <tr>
                        <th>#</th>
                        <th>Stud Id</th>
                        <th>Name</th>
                        <th>Whatsapp No</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Admission Date</th>
                        <th>Monthly Fees</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td colspan="12" class="font-weight-bold text-center">
                            <span class="text-danger">Admission Data Not Found.</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>
<?php 
include "../component/footer.php";
?>