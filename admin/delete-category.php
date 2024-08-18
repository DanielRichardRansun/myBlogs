<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);



    //for letter
    //mengubah post menjadi "uncategory" jika category dalam post tersebut dihapus
    $update_query = "UPDATE posts SET category_id=7 WHERE category_id=$id";
    $update_result = mysqli_query($connection, $update_query);

    if (!mysqli_errno($connection)) {
        //delete category
        $query = "DELETE FROM categories WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);
        $_SESSION['delete-category-success'] = "Category Deleted Successfully";
    }
}

header("location:" . ROOT_URL . "admin/manage-categories.php");
die();
