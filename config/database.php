<?php
require 'config/constans.php';

// Connect to database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek jika koneksi gagal
if ($connection->connect_errno) {
    die('Connection failed: ' . $connection->connect_error);
}
