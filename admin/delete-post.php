<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch post dari database untuk menghapus thumbnail dari folder images
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);

    // Pastikan hanya satu record/post yang diambil
    if (mysqli_num_rows($result) == 1) {
        $post = mysqli_fetch_assoc($result);
        $thumbnail_name = $post['thumbnail'];
        $thumbnail_path = '../images/' . $thumbnail_name;

        if ($thumbnail_name && file_exists($thumbnail_path)) {
            unlink($thumbnail_path); // Hapus file thumbnail
        }

        // Lanjutkan dengan penghapusan pos dari database
        $delete_post_query = "DELETE FROM posts WHERE id=$id LIMIT 1";
        $delete_post_result = mysqli_query($connection, $delete_post_query);

        if (!mysqli_errno($connection)) {
            $_SESSION['delete-post-success'] = "Post deleted successfully.";
        } else {
            $_SESSION['delete-post-error'] = "Failed to delete post from the database.";
        }
    } else {
        $_SESSION['delete-post-error'] = "Post not found.";
    }
} else {
    $_SESSION['delete-post-error'] = "Invalid post ID.";
}

// Redirect ke halaman manajemen posts setelah operasi selesai
header('location: ' . ROOT_URL . 'admin/index.php');
exit();
