<?php
require '../partials/header.php';

//cek status login jika sudah logout tidak bisa mengakses admin lagi
if (!isset($_SESSION['user-id'])) {
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}
