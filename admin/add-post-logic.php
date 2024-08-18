<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    //featured akan jadi 0 jika tidak dicentang
    $is_featured = $is_featured == 1 ? 1 : 0;

    //validate form data
    if (!$title) {
        $_SESSION['add-post'] = "Enter post title";
    } elseif (!$category_id) {
        $_SESSION['add-post'] = "Enter post cataegories";
    } elseif (!$body) {
        $_SESSION['add-post'] = "Enter post body";
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-post'] = "Choose post thumbnail";
    } else {
        //JIKA BERHASIL
        //mengganti nama thumbnail
        $time = time();
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        //memastikan file png jpg jpeg
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if (in_array($extension, $allowed_files)) {
            //check image tidak besar dari 3mb+
            if ($thumbnail['size'] < 3_000_000) {
                //upload thumbnai
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-post'] = "File is too big. Should be less than 3mb";
            }
        } else {
            $_SESSION['add-post'] = "FIle sould be png,jpg,jpeg";
        }
    }
    //jika ada error keembali ke form
    if (isset($_SESSION["add-post"])) {
        $_SESSION["add-post-data"] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        //ubah is_featured semua post menjadi 0 jika is_featured untuk post ini 1
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }
        //insert post ke database
        $query = "INSERT INTO posts (title,body,thumbnail,category_id,author_id,is_featured) VALUES ('$title','$body','$thumbnail_name','$category_id','$author_id','$is_featured')";
        $result = mysqli_query($connection, $query);

        if (!mysqli_errno($connection)) {
            $_SESSION["add-post-success"] = "New Post added Successfully";
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
    }
}
header('location: ' . ROOT_URL . 'admin/add-post.php');
die();
