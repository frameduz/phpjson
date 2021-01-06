<input type="hidden" id="productId" name="productId">
<div class="form-group">
    <label for="productName">Product Name</label>
    <input type="hidden" id="productId" name="productId" value="<?= $formProduct['form']['productId']; ?>">
    <input type="text" class="form-control" id="productName" name="productName"
        value="<?= $formProduct['form']['productName']; ?>" required>
</div>
<div class="form-group">
    <label for="categoryId">Category</label>
    <select class="custom-select d-block w-100" id="categoryId" name="categoryId" required>
        <?php 
        foreach ($category as $key => $cat) {
            $selected = ($cat['categoryId'] == $formProduct['form']['categoryId']) ? 'selected' : '';
            echo '<option value="'.$cat['categoryId'].'" '.$selected.'>'.$cat['categoryName'].'</option>';
        }
        ?>
    </select>
</div>
<div class="form-group">
    <label for="productPrice">Price</label>
    <input type="text" class="form-control" id="productPrice" name="productPrice"
        value="<?= $formProduct['form']['productPrice']; ?>" required>
</div>
<div class="form-group">
    <label for="productImage">Image <small><?= $formProduct['form']['productImage']; ?></small></label>
    <input type="file" class="form-control-file" id="productImage" name="productImage">
</div>
<script>
    $("#formModalLabel").html("<?= $formProduct['title']; ?>");
</script>