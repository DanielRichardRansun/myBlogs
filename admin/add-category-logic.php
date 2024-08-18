<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    //get form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$title) {
        $_SESSION['add-category'] = "Enter your title";
    } elseif (!$description) {
        $_SESSION['add-category'] = "Enter your description";
    }

    //lkembali ke category page dengan form data jika ada invalid
    if (isset($_SESSION['add-category'])) {
        $_SESSION['add-category-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-category.php');
        die();
    } else {
        $query = "INSERT INTO categories (title,description) value ('$title','$description')";
        $result = mysqli_query($connection, $query);
        if (mysqli_errno($connection)) {
            $_SESSION["add-category"] = "Couldn't add category";
            header("location: " . ROOT_URL . "admin/add-category.php");
            die();
        } else {
            $_SESSION["add-category-success"] = "Success add category $title";
            header("location: " . ROOT_URL . "admin/manage-categories.php");
            die();
        }
    }
}
