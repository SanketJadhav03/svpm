<?php
include "../config/connection.php";
include "../component/header.php";
include "../component/sidebar.php";
if (isset($_POST["product_save"])) { 
    echo $_FILES["product_image"]["tmp_name"];
    // $product_name = $_POST["product_name"];  
    // $product_qty = $_POST["product_qty"];  
    // $product_price = $_POST["product_price"];  
    // $product_brand = $_POST["product_brand"];  
    // $product_category = $_POST["product_category"];  
    // $product_total_price = $_POST["product_total_price"];  
    // $product_discount = $_POST["product_discount"];  
    // $product_discount_price = $_POST["product_discount_price"];  
    // $product_final_price = $_POST["product_final_price"];  
    // $product_image = $_POST["product_image"];  
    
    // if (mysqli_query($conn, $insertQuery)) {
    //     $_SESSION["success"] = "Product Created Successfully!";
    //     echo "<script>window.location = 'index.php';</script>";
    // }
}
?>
<div class="content-wrapper p-2">
    <form action="new.php" method="post" onsubmit="validation();" enctype="multipart/form-data">
        <div class="card ">
            <div class="card-header ">
                <div class="d-flex p-2 justify-content-between">
                    <div class="h5 font-weight-bold">Create Product</div>
                    <a href="index.php" class="btn btn-info shadow font-weight-bold"> <i class="fa fa-eye"></i>&nbsp; Products List</a>
                </div>

            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-4">
                        <label for="">Product Name <span class="text-danger font-weight-bold"> *</span></label>
                        <input type="text" class="form-control font-weight-bold" name="product_name" id="product_name" placeholder="Product Name">
                    </div>
                    <div class="col-4">
                        <label for=""> Brand </label>
                        <select name="product_brand" class="form-control font-weight-bold">
                            <option value="">Default Brand </option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for=""> Category </label>
                        <select name="product_category" class="form-control font-weight-bold">
                            <option value="">Default Category </option>
                        </select>
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Price</label>
                        <input type="text" class="form-control font-weight-bold" name="product_price" id="product_price" placeholder="Price">
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Qty </label>
                        <input type="text" class="form-control font-weight-bold" name="product_qty" id="product_qty" placeholder="Qauntity">
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Total Price </label>
                        <input type="text" class="form-control font-weight-bold" name="product_total_price" id="product_total_price" placeholder="Total Price">
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Discount ( % ) </label>
                        <input type="text" class="form-control font-weight-bold" name="product_discount" id="product_discount" placeholder="Discount ( % )">
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Discount Value </label>
                        <input type="text" class="form-control font-weight-bold" name="product_discount_price" id="product_discount_price" placeholder="Discount Value">
                    </div>
                    <div class="col-3 mt-3 mb-1">
                        <label for="">Final Value </label>
                        <input type="text" class="form-control font-weight-bold" name="product_final_price" id="product_final_price" placeholder="Final Value">
                    </div>
                    <div class="col-6 mt-3 ">
                        <label for="">Image</label>
                        <div class="row">
                            <div class=" col-6">
                                <input type="file" onchange="showImage();"  class="form-control"  name="product_image" id="product_image">
                            </div>
                            <div class="col-6"> 
                                <img src="" height="100" width="100" type="image/*" alt="Product Image" id="show_image">
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="card-footer ">
                <div class="d-flex p-2 justify-content-end">
                    <button name="product_save" type="submit" class="btn btn-primary shadow font-weight-bold"> <i class="fa fa-save "></i>&nbsp; Add Product</button>
                    &nbsp;
                    <button type="reset" class="btn btn-danger shadow font-weight-bold"> <i class="fas fa-times "></i>&nbsp; Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function showImage() {
    const image = document.getElementById('show_image');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            image.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}
    function validation() { 
        var product_name = document.getElementById("product_name"); 
        if (product_name.value == "") {
            product_name.focus();
            event.preventDefault();
        }  
    }
</script>
<?php
include "../component/footer.php";
?>