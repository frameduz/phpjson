<?php 
extract($listProduct);
if (empty($value)) {
    echo '<div class="empty-product text-center">
            <div class="container">
                <img src="asset/_image/no-results.png" alt="">
                <h1 class="display-4">No Results Founds</h1>
            </div>
        </div>';
}
else {
    echo '<div class="row">';
    foreach ($value as $key => $list) {
        echo '<div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card mb-4 shadow-sm">
                    <img src="'.$list['productImage'].'" height="200px" alt="">
                    <div class="card-body">
                        <p class="card-text">'.$list['productName'].'</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="#" class="btn btn-sm btn-outline-secondary btn-form" id="'.$list['productId'].'" data-toggle="modal" data-target="#formModal">Edit</a>
                                <a href="crud/index.php?act=delete&id='.$list['productId'].'" onclick="return confirm(\'Yakin data ini akan dihapus ?\') ? true : false;" class="btn btn-sm btn-outline-secondary btn-delete">Hapus</a>
                            </div>
                            <small class="text-muted">Rp. '.number_format($list['productPrice'], 0, ',', '.').'</small>
                        </div>
                    </div>
                </div>
            </div>';
    }
    echo '</div>';
}

pagging($page, $limit, $count);
?>