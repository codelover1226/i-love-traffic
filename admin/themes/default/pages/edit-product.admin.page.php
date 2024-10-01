<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/incs/header.theme.php";
$productController = new ProductsController();
$id = $_GET["id"];
$flag = $productController->updateProduct($id);
$productInfo = $productController->getProductDetails($id);
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Store & Affiliates</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Store</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
        <a href="products.php" class="text-primary hover:underline">Products</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <?php if (empty($productInfo)) : ?>
                    <div class="alert alert-danger">Couldn't find the product.</div>
                <?php else : ?>
                    <form class="forms-sample" action="" method="POST">
                        <div class="form-group">
                            <label for="noticeContent">Product Title</label>
                            <input type="text" class="form-input" name="product_title" placeholder="Enter product title" value="<?= $productInfo['product_title'] ?>">
                            <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Product Description</label>
                            <textarea class="form-input" rows="15" name="product_description" placeholder="Enter your product description"><?= $productInfo['product_description'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="noticeContent">Price</label>
                            <input type="number" step="0.1" class="form-input" id="exampleTextarea1" name="product_price" placeholder="Enter your product price" value="<?= $productInfo['product_price'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Is this product active ?</label>
                            <select class="form-input" name="status">
                                <option value="1" <?= $productInfo['status'] == 1 ? "selected" : "" ?>>Yes</option>
                                <option value="2" <?= $productInfo['status'] == 2 ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Is this special offer ?</label>
                            <select class="form-input" name="special_offer">
                                <option value="2" <?= $productInfo['special_offer'] == 2 ? "selected" : "" ?>>No</option>
                                <option value="1" <?= $productInfo['special_offer'] == 1 ? "selected" : "" ?>>Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Want to hide this from store page ? ?</label>
                            <select class="form-input" name="hidden">
                                <option value="1" <?= $productInfo['hidden'] == 1 ? "selected" : "" ?>>Yes</option>
                                <option value="2" <?= $productInfo['hidden'] == 2 ? "selected" : "" ?>>No</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Update Product</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (isset($flag) && isset($flag["success"])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flag["success"] == true) : ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php else : ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>
<?php require_once "themes/default/incs/footer.theme.php"; ?>