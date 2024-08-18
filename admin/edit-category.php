<?php
include 'partials/header.php';

if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

  //fetch category dari database
  $query = "SELECT * FROM categories WHERE id=$id";
  $result = mysqli_query($connection, $query);
  if (mysqli_num_rows($result) == 1) {
    $category = mysqli_fetch_assoc($result);
  }
} else {
  header('location: ' . ROOT_URL . 'admin/manage-categories.php');
  die();
}
?>

<section class="form_section">
  <div class="container form_section-container">
    <h2>Edit Categoriy</h2>
    <form action="<?= ROOT_URL ?>admin/edit-category-logic.php" method="POST">
      <input type="hidden" name="id" value="<?= $category['id'] ?>" />
      <input type="text" name="title" value="<?= $category['title'] ?>" placeholder="Title" />
      <textarea rows="4" name="description" placeholder="Description"><?= $category['description'] ?></textarea>
      <button name="submit" type="submit" class="btn">Update Category</button>
    </form>
  </div>
</section>
<!-- Footer -->
<?php
include '../partials/footer.php';
?>