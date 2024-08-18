<?php
require 'config/constans.php';

// Menghapus semua variabel sesi
session_start();
session_unset();

// Menghancurkan sesi
session_destroy();

// Redirect ke halaman utama
header('Location: ' . ROOT_URL);
die();
