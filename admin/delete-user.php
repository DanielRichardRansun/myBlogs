<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch user dari database
    $query = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $avatar_name = $user["avatar"];
        $avatar_path = '../images/' . $avatar_name;

        // Hapus gambar yang ada
        if (file_exists($avatar_path)) {
            unlink($avatar_path);
        }

        // Delete user dari database
        $delete_user_query = "DELETE FROM users WHERE id=$id LIMIT 1";
        $delete_user_result = mysqli_query($connection, $delete_user_query);

        if (mysqli_errno($connection)) {
            $_SESSION["delete-user"] = "Couldn't delete '{$user['firstname']} {$user['lastname']}'";
        } else {
            $_SESSION["delete-user-success"] = "'{$user['firstname']} {$user['lastname']}' has been deleted successfully.";
        }
    } else {
        $_SESSION["delete-user"] = "User not found.";
    }
} else {
    $_SESSION["delete-user"] = "Invalid user ID.";
}

// Redirect ke halaman manage-user.php
header("Location: " . ROOT_URL . "admin/manage-user.php");
exit();
