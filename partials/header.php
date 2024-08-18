<?php
session_start();
require 'config/database.php';

//fetch user saat ini dari database
if (isset($_SESSION['user-id'])) {
  $id = filter_var($_SESSION['user-id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT avatar FROM users WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $avatar = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE php>
<php lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHP Blog & Mysql Application with Admin Panel</title>
    <link rel="stylesheet" href="<?= ROOT_URL ?>/css/style.css" />
    <link
      rel="stylesheet"
      href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"
      rel="preconnect"
      href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet" />
  </head>

  <body>
    <!-- Navigation -->
    <nav>
      <div class="container nav_container">
        <a href="<?= ROOT_URL ?>index.php" class="nav_logo">myBlogs</a>
        <ul class="nav_items">
          <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
          <li><a href="<?= ROOT_URL ?>about.php">About</a></li>
          <li><a href="<?= ROOT_URL ?>services.php">Services</a></li>
          <li><a href="<?= ROOT_URL ?>contact.php">Contact</a></li>
          <!-- Jika belom pernah sign in tampilkan signin jika sudah tampilkan logo -->
          <?php if (isset($_SESSION['user-id'])): ?>
            <li class="nav_profile">
              <div class="avatar">
                <img src="<?= ROOT_URL . 'images/' . $avatar['avatar'] ?>" />
              </div>
              <ul>
                <li>
                  <a href="<?= ROOT_URL ?>admin/index.php">Dashboard</a>
                </li>

                <li>
                  <a href="<?= ROOT_URL ?>logout.php">Logout</a>
                </li>
              </ul>
            </li>
          <?php else: ?>
            <li><a href="<?= ROOT_URL ?>signin.php">Signin</a></li>
          <?php endif ?>
        </ul>

        <button id="open_nav-btn"><i class="uil uil-bars"></i></button>
        <button id="close_nav-btn"><i class="uil uil-multiply"></i></button>
      </div>
    </nav>
    <!-- End Navigation -->