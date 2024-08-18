<?php
require 'config/database.php';

// Memastikan tombol edit post terklik
$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
$previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
$is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
$thumbnail = $_FILES['thumbnail'];

// Set is_featured menjadi 0 jika checkbox tidak tercentang
$is_featured = $is_featured == 1 ? 1 : 0;

// Cek dan validasi nilai input
if (!$title) {
    $_SESSION['edit-post'] = "Couldn't update post. Invalid form data on edit post page.";
} elseif (!$category_id) {
    $_SESSION['edit-post'] = "Couldn't update post. Invalid form data on edit post page.";
} elseif (!$body) {
    $_SESSION['edit-post'] = "Couldn't update post. Invalid form data on edit post page.";
} else {
    // Hapus thumbnail yang ada jika thumbnail baru tersedia
    if ($thumbnail['name']) {
        $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
        if (file_exists($previous_thumbnail_path)) { // Cek apakah file ada
            unlink($previous_thumbnail_path); // Menghapus file thumbnail sebelumnya
        }

        // Bekerja dengan thumbnail baru
        // Mengganti nama gambar
        $time = time(); // Membuat setiap nama gambar unggahan unik menggunakan timestamp saat ini
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        // Memastikan file adalah gambar
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = pathinfo($thumbnail_name, PATHINFO_EXTENSION);

        if (in_array($extension, $allowed_files)) {
            // Memastikan ukuran file tidak terlalu besar (lebih dari 2MB)
            if ($thumbnail['size'] <= 2000000) {
                // Lanjutkan dengan proses unggah file
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path); // Memindahkan file ke lokasi tujuan
            } else {
                $_SESSION['edit-post'] = "Couldn't update post. Thumbnail file size must be less than 2MB.";
            }
        } else {
            $_SESSION['edit-post'] = "Couldn't update post. Only PNG, JPG, and JPEG files are allowed.";
        }
    }

    // Set nama thumbnail jika yang baru diunggah, jika tidak, gunakan nama thumbnail lama
    $thumbnail_to_insert = $thumbnail['name'] ? $thumbnail_name : $previous_thumbnail_name;

    // Set is_featured semua pos menjadi 0 jika is_featured untuk pos ini adalah 1
    if ($is_featured == 1) {
        $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
        mysqli_query($connection, $zero_all_is_featured_query);
    }

    // Query untuk memperbarui data pos
    $query = "UPDATE posts SET title='$title', body='$body', thumbnail='$thumbnail_to_insert',
              category_id=$category_id, is_featured=$is_featured WHERE id=$id LIMIT 1";
    $result = mysqli_query($connection, $query);

    // Tambahkan penanganan kesalahan jika diperlukan
    if ($result) {
        $_SESSION['edit-post-success'] = "Post updated successfully.";
        header('location: ' . ROOT_URL . 'admin/index.php');
        exit(); // Pastikan untuk keluar setelah redirect
    } else {
        $_SESSION['edit-post'] = "Couldn't update post. Database error.";
    }
}

// Redirect ke halaman manajemen form jika form tidak valid
header('location: ' . ROOT_URL . 'admin/');
die(); // Pastikan untuk keluar setelah redirect
