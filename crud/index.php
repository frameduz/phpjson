<?php 

require_once '../main.php';

if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'list':
            // $page = intval($_POST['page']);
            $listProduct = getListProduct($_POST);
            // print_r($listProduct);
            require_once CRUD . 'list.php';
            break;

        case 'form':
            $id = $_POST['id'];
            $formProduct = getFormProduct($id);
            $category = getJSON('category');
            require_once CRUD . 'form.php';
            break;

        case 'save':
            // print('<pre>'.print_r($_FILES, true).'</pre>'); die;
            $formProduct = getFormProduct($_POST['productId']);
            $index = $formProduct['index'];
            $form = $formProduct['form'];
            $data = getJSON('product');
            $error_message = '';

            foreach ($form as $key => $value) {
                if(isset($_POST[$key]) && !empty($_POST[$key])) $form[$key] = $_POST[$key];
            }

            // Check upload file
            $fileUpload = uploadImage('productImage');
            if ($fileUpload['status']) {
                $form['productImage'] = $fileUpload['upload'];
            }

            // Simpan/Edit Data
            if ($index === false) {
                //array_push($data, $form);
                array_unshift($data, $form);
                $error_message = '<div class="alert alert-success" role="alert">Sukses, data telah disimpan</div>';
            }
            else {
                $data[$index] = $form;
                $error_message = '<div class="alert alert-success" role="alert">Sukses, data telah diubah</div>';
            }

            setJSON($data, 'product');
            $_SESSION['error_message'] = $error_message;
            header('Location: '.$_SERVER['HTTP_REFERER']);
            break;

        case 'delete':
            $formProduct = getFormProduct($_GET['id']);
            $index = $formProduct['index'];
            $form = $formProduct['form'];
            $data = getJSON('product');
            $error_message = '<div class="alert alert-success" role="alert">Sukses, data telah dihapus</div>';
            
            // Hapus file upload
            deleteImage($form['productImage']);
            // Hapus data
            unset($data[$index]);
            $result = array_values($data); // Simpan perubahan data ke variabel baru 
            setJSON($result, 'product');
            $_SESSION['error_message'] = $error_message;
            header('Location: '.$_SERVER['HTTP_REFERER']);
            break;
        
        default:
            header('Location: ../');
            break;
    }
}
else {
    header('Location: ../');
}

?>
