<?php
session_start();
require 'config/database.php';

//ke form data jika button submit diclick
if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_VALIDATE_BOOLEAN);
    $avatar = $_FILES['avatar'];

    // Validate input
    if (!$firstname) {
        $_SESSION['add-user'] = "Please enter your First Name";
    } elseif (!$lastname) {
        $_SESSION['add-user'] = "Please enter your Last Name";
    } elseif (!$username) {
        $_SESSION['add-user'] = "Please enter your Username";
    } elseif (!$email) {
        $_SESSION['add-user'] = "Please enter your Valid Email";
    } elseif (empty($createpassword) || strlen($createpassword) < 8 || empty($confirmpassword)) {
        $_SESSION['add-user'] = "Password must be 8+ characters";
    } elseif ($createpassword !== $confirmpassword) {
        $_SESSION['add-user'] = "Passwords do not match";
    } elseif (!$avatar['name']) {
        $_SESSION['add-user'] = "Please add an avatar";
    } else {
        // Hash password
        $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);
        // Cek jika username/email sudah ada di database
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $user_check_result = mysqli_query($connection, $user_check_query);
        if (mysqli_num_rows($user_check_result) > 0) {
            $_SESSION["add-user"] = "Username or Email already exists";
        } else {
            // UNTUK AVATAR
            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = '../images/' . $avatar_name; // Mengarah ke images di satu tingkat atas

            // Memastikan file adalah gambar
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $avatar_name);
            $extension = end($extension);
            if (in_array($extension, $allowed_files)) {
                if ($avatar['size'] < 3000000) {
                    // Upload avatar
                    move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                } else {
                    $_SESSION['add-user'] = 'File size too big. Should be less than 3MB';
                }
            } else {
                $_SESSION['add-user'] = 'File should be png, jpg, jpeg';
            }
        }
    }

    // Kembali ke add-user jika ada masalah
    if (isset($_SESSION['add-user'])) {
        $_SESSION['add-user-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-user.php');
        die();
    } else {
        // Masukkan data ke database
        $insert_user_query = "INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES ('$firstname', '$lastname', '$username', '$email', '$hashed_password', '$avatar_name', '$is_admin')";
        $insert_user_result = mysqli_query($connection, $insert_user_query);
        if (!mysqli_errno($connection)) {
            $_SESSION['add-user-success'] = "Add User Success. New User $firstname $lastname added.";
            header('location: ' . ROOT_URL . 'admin/manage-user.php');
            die();
        }
    }
} else {
    // Jika tombol tidak diklik
    header('location: ' . ROOT_URL . 'admin/add-user.php');
    die();
}
