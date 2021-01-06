<?php 

session_start();

define('ASSET', dirname(__FILE__).'/asset/');
define('CRUD', dirname(__FILE__).'/crud/');

function getJSON($file) {
    $file = ASSET.$file.'.json';
    $result = [];
    if (!file_exists($file)) {
        return $result;
    }

    $json = file_get_contents($file);
    $json = json_decode($json, true);
    $result = ($json != null) ? $json : $result;
    return $result;
}

function setJSON($data, $file) {
    $file = ASSET.$file.'.json';
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $json);
}

// function getListProduct($page = 1) {
//     $product = getJSON('product');
//     $total = count($product);
//     $limit = 4;
//     $cursor = ($page - 1) * $limit;
//     $result = array_slice($product, $cursor, $limit);
//     return $result;
// }

function getListProduct($params) {
    $product = getJSON('product');
    $product = array_filter($product, function($item) use ($params) {
        return (preg_match('/'.$params['category'].'/i', $item['categoryId']) & preg_match('/'.$params['search'].'/i', $item['productName']));
    });

    $page = intval($params['page']);
    $limit = 4;
    $count = count($product);
    $cursor = ($page - 1) * $limit;
    return array(
        'page' => $page,
        'limit' => $limit,
        'count' => $count,
        'value' => array_slice($product, $cursor, $limit),
    );
}

function getFormProduct($id = '') {
    $product = getJSON('product');
    $index = array_search($id, array_column($product, 'productId'));
    if ($index !== false) {
        return array(
            'title' => 'Edit Product',
            'index' => $index,
            'form' => $product[$index]
        );
    }
    else {
        return array(
            'title' => 'Add Product',
            'index' => false,
            'form' => array(
                'productId' => date('YmdHis'),
                'categoryId' => '',
                'productImage' => 'https://via.placeholder.com/300',
                'productName' => '',
                'productPrice' => 0
            )
        );
    }
}

function uploadImage($file) {
    $result = array(
        'status' => false,
        'message' => '',
        'upload' => ''
    );

    if (isset($_FILES[$file]) && !empty($_FILES[$file]['name'])) {
        $fileUpload = $_FILES[$file];
        $fileExtension = pathinfo($fileUpload['name'], PATHINFO_EXTENSION);
        $fileName = date('YmdHis').'.'.$fileExtension;
        $status = move_uploaded_file($fileUpload['tmp_name'], ASSET.'_image/'.$fileName);
        if ($status) {
            $result['status'] = true;
            $result['message'] = 'File berhasil diupload';
            $result['upload'] = 'asset/_image/'.$fileName;
        }
        else {
            $result['message'] = 'File gagal diupload';
        }
    }

    return $result;
}

function deleteImage($file) {
    $fileName = end(explode('/', $file));
    $fileUpload = ASSET.'_image/'.$fileName;
    if (file_exists($fileUpload)) {
        unlink($fileUpload);
    }
}

function pagging($page, $limit, $count) {
    /**
     * Format view pagging
     * [1] -> jika total halaman = 1, maka tidak usah tampilkan page
     * [1] 2 3
     * [1] 2 3 ... next last
     * first prev ... [2] 3 4 .. next last
     * first prev ... [8] 9 10
     */

    // Template pagging
    $page_number = '<li class="page-item"><a class="page-link" href="#" data-page={number}>{page}</a></li>';
    $page_active = '<li class="page-item active"><span class="page-link" href="#" data-page={number}>{page}</span></li>';
    $paginations = '<nav aria-label="Page navigation example"><ul class="pagination">{pagging}</ul></nav>';
    $pagging = array();

    $total_pages = ceil($count/$limit);
    $prev_number = ($page > 1) ? $page - 1 : 1;
    $next_numner = ($page < $total_pages) ? $page + 1 : $total_pages;

    $btn_first = str_replace(['{number}', '{page}'], [1, '&laquo;'], $page_number);
    $btn_lasts = str_replace(['{number}', '{page}'], [$total_pages, '&raquo;'], $page_number);
    $btn_prev = str_replace(['{number}', '{page}'], [$prev_number, '&lsaquo;'], $page_number);
    $btn_next = str_replace(['{number}', '{page}'], [$next_numner, '&rsaquo;'], $page_number);
    $btn_dots = str_replace(['{number}', '{page}', 'active'], ['', '...', 'disabled'], $page_active);
    $btn_active = str_replace(array('{number}','{page}'), array('', $page), $page_active);

    if ($total_pages > 1) {
        if ($page > 3) {
            array_push($pagging, $btn_first);
            array_push($pagging, $btn_prev);
            array_push($pagging, $btn_dots);
        }

        for ($i = ($page - 2); $i < $page; $i++) { 
            if ($i < 1) continue;
            array_push($pagging, str_replace(['{number}', '{page}'], [$i, $i], $page_number));
        }

        array_push($pagging, $btn_active);

        for ($i = ($page + 1); $i < ($page + 3); $i++) { 
            if ($i > $total_pages) break;
            array_push($pagging, str_replace(['{number}', '{page}'], [$i, $i], $page_number));
        }

        if (($page + 2) < $total_pages) {
            array_push($pagging, $btn_dots);
        }

        if ($page < ($total_pages - 2)) {
            array_push($pagging, $btn_next);
            array_push($pagging, $btn_lasts);
        }
    }
    

    $result = str_replace('{pagging}', implode($pagging), $paginations);
    echo $result;
}

?>
