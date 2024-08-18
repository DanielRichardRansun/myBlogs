<?php
include 'partials/header.php';

// Fetch posts if id is set
if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * FROM posts WHERE category_id=$id ORDER BY date_time DESC";
  $result = mysqli_query($connection, $query);

  // Fetch category dari tabel categories dengan menggunakan category_id
  $category_query = "SELECT * FROM categories WHERE id=$id";
  $category_result = mysqli_query($connection, $category_query);
  $category = mysqli_fetch_assoc($category_result);
} else {
  header('Location: ' . ROOT_URL . 'blog.php');
  die();
}
?>

<!-- Title -->
<header class="category_title">
  <h2><?= $category['title'] ?></h2>
</header>
<!-- End Title -->

<!-- Section Posts -->
<!-- jika tidak ada post sama sekali -->
<?php if (mysqli_num_rows($result) > 0): ?>
  <section class="posts">
    <div class="container posts_container">
      <?php while ($post = mysqli_fetch_assoc($result)) : ?>
        <article class="post">
          <div class="post_thumbnail">
            <img src="./images/<?= $post['thumbnail'] ?>" />
          </div>
          <div class="post_info">
            <h3 class="post_title">
              <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
            </h3>
            <p class="post_body">
              <!-- Membatasi tampilan body sampai 300 huruf saja -->
              <?= substr($post['body'], 0, 150) ?>...
            </p>
            <div class="post_author">
              <?php
              //fetch author dari tabel users dengan author_id
              $author_id = $post['author_id'];
              $author_query = "SELECT * FROM users WHERE id=$author_id";
              $author_result = mysqli_query($connection, $author_query);
              $author = mysqli_fetch_assoc($author_result);
              ?>
              <div class="post_author-avatar">
                <img src="images/<?= $author['avatar'] ?>" />
              </div>
              <div class="post_author-info">
                <h5><?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                <small><?= date("M,d,Y - H:i", strtotime($post['date_time'])) ?></small>
              </div>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
    </div>
  </section>
<?php else : ?>
  <div class="alert_message error lg">
    <p>No posts found in this category.</p>
  </div>
<?php endif; ?>
<!-- End Post -->

<!-- Category Button Section -->
<section class="category_buttons">
  <div class="container category_buttons-container">
    <?php
    $all_categories_query = "SELECT * FROM categories";
    $all_categories_result = mysqli_query($connection, $all_categories_query);
    ?>
    <?php while ($category = mysqli_fetch_assoc($all_categories_result)) : ?>
      <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category_button"><?= $category['title'] ?></a>
    <?php endwhile; ?>
  </div>
</section>

<?php
include 'partials/footer.php';
?>