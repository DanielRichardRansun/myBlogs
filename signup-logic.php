<?php
session_start();
require 'config/database.php';

//ke signup-logic jika button submit diclick
if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];


    //validate input
    if (!$firstname) {
        $_SESSION['signup'] = "Please enter your First Name";
    } elseif (!$lastname) {
        $_SESSION['signup'] = "Please enter your Last Name";
    } elseif (!$username) {
        $_SESSION['signup'] = "Please enter your Username";
    } elseif (!$email) {
        $_SESSION['signup'] = "Please enter your Valid Email";
    } elseif (strlen($createpassword) < 8 || strlen($confirmpassword) < 8) {
        $_SESSION['signup'] = "Password must be 8+ charcters";
    } elseif (!$avatar['name']) {
        $_SESSION['signup'] = "Please add avatar";
    } else {
        //check jika password tidak sesuai
        if ($createpassword !== $confirmpassword) {
            $_SESSION["signup"] = "Password do not match";
        } else {
            //hash password
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);

            //jika username/email sudah ada didatabase
            $user_chech_quary = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $user_chech_result = mysqli_query($connection, $user_chech_quary);
            if (mysqli_num_rows($user_chech_result) > 0) {
                $_SESSION["signup"] = "Username or Email already exist";
            } else {
                //UNTUK AVATAR
                //mengubah nama avatar
                $time = time(); //membuat nama avatar menjadi unik dengan timestamp
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = 'images/' . $avatar_name;

                //memastikan file adalah gambar
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extention = explode('.', $avatar_name);
                $extention = end($extention);
                if (in_array($extention, $allowed_files)) {
                    //memastikan file tidak lebih dari 3mb
                    if ($avatar['size'] < 3000000) {
                        //upload avatar
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    } else {
                        $_SESSION['signup'] = 'File size too big. Should be less than 3mb';
                    }
                } else {
                    $_SESSION['signup'] = 'File sould be png,jpg,jpeg';
                }
            }
        }
    }
    //kembali ke signup jika ada suatu masalah
    if (isset($_SESSION['signup'])) {
        //kembalikan data ke signup page
        $_SESSION['signup-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
    } else {
        //masukan data ke database
        $insert_user_query = "INSERT INTO users SET firstname = '$firstname',lastname = '$lastname' ,username = '$username' ,email = '$email' ,password = '$hashed_password',avatar = '$avatar_name' ,is_admin = 0";
        $insert_user_result = mysqli_query($connection, $insert_user_query);
        if (!mysqli_errno($connection)) {
            //pindah ke login dengan message berhasil 
            $_SESSION['signup-success'] = "Registration succesful. Please Login";
            header('location: ' . ROOT_URL . 'signin.php');
            die();
        }
    }
} else {
    //jika tombol tidak diclick kembali ke singup, contoh langsung menginputkan link signup-logic akan dikembalikaan ke signup.php
    header('location: ' . ROOT_URL . 'signup.php');
    die();
}
