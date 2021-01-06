<?php require_once 'main.php'; ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="#" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Simple Crud + Upload</title>
</head>

<body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">List Product</h5>
    </div>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">

        <form id="formList" action="" method="post" class="form-inline my-2 my-lg-0 mr-md-auto"
            onsubmit="return false;">
            <select name="category" id="category" class="filter-list custom-select my-1 mr-sm-2">
                <option value="" selected>Semua Kategori</option>
                <?php 
                    $category = getJSON('category');
                    foreach ($category as $key => $cat) {
                        echo '<option value="'.$cat['categoryId'].'" '.$selected.'>'.$cat['categoryName'].'</option>';
                    }
                ?>
            </select>
            <input name="search" id="search" class="form-control mr-sm-2" type="text" placeholder="Search">
            <input type="hidden" name="page" id="page" value="1">
        </form>
        <a class="btn btn-outline-primary btn-form" id="" data-toggle="modal" data-target="#formModal" href="#">Add
            Product</a>
    </div>

    <main role="main">

        <div class="album py-5 bg-light">
            <div class="container-fluid">

                <?php 
                if (isset($_SESSION['error_message'])) {
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="list-loader text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="list-product"></div>

            </div>
        </div>

    </main>

    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-product" class="form-product" action="crud/index.php?act=save" method="post"
                        enctype="multipart/form-data"></form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-product" value="submit">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

    <script>
    const actionUrl = "crud/index.php?act=";
    const listLoader = $(".list-loader");
    const listProduct = $(".list-product");
    const formProduct = $(".form-product");

    $(".filter-list").on("change", function(event) {
        event.preventDefault();
        $("#formList").submit();
    });

    $("#formList").on("submit", function(event) {
        const params = $(this).serialize();
        listLoader.show();
        listProduct.hide();
        setTimeout(() => {
            $.ajax({
                url: actionUrl + "list",
                data: params,
                type: "post",
                success: function(data) {
                    listLoader.hide();
                    listProduct.html(data);
                    listProduct.show();
                }
            })
        }, 500);
    });

    $(document).on("click", ".btn-form", function(event) {
        event.preventDefault();
        let productId = this.id;
        $.ajax({
            url: actionUrl + "form",
            data: {
                id: productId
            },
            type: "post",
            success: function(data) {
                formProduct.html(data);
            }
        });
    });

    $(document).on("click", ".page-item a", function(event) {
        event.preventDefault();
        const page = $(this).data("page");
        $("#page").val(page);
        $("#formList").submit();
    });

    $("#formList").submit();
    </script>
</body>

</html>