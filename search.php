<?php
require 'partials/header.php';

if (isset($_GET['search']) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Query untuk mencari posts berdasarkan judul
    $query = "SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY date_time DESC";
    $posts = mysqli_query($connection, $query);

    if (!$posts) {
        // Jika query gagal, redirect ke blog.php
        header('Location: ' . ROOT_URL . 'blog.php');
        die();
    }
} else {
    // Jika parameter 'search' dan 'submit' tidak ada, redirect ke blog.php
    header('Location: ' . ROOT_URL . 'blog.php');
    die();
}
?>

<!-- Section Posts -->
<?php if (mysqli_num_rows($posts) > 0): ?> <!-- jika tidak ada post saat disearch -->
    <section class="posts section_extra-margin">
        <div class="container posts_container">
            <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
                <article class="post">
                    <div class="post_thumbnail">
                        <img src="./images/<?= ($post['thumbnail']) ?>" alt="<?= ($post['title']) ?>" />
                    </div>
                    <div class="post_info">
                        <?php
                        // Fetch category dari table categories dengan menggunakan category_id
                        $category_id = $post['category_id'];
                        $category_query = "SELECT * FROM categories WHERE id=$category_id";
                        $category_result = mysqli_query($connection, $category_query);
                        $category = mysqli_fetch_assoc($category_result);
                        ?>
                        <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category_button"><?= ($category['title']) ?></a>
                        <h3 class="post_title">
                            <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= ($post['title']) ?></a>
                        </h3>
                        <p class="post_body">
                            <!-- Membatasi tampilan body sampai 150 huruf saja -->
                            <?= (substr($post['body'], 0, 150)) ?>...
                        </p>
                        <div class="post_author">
                            <?php
                            // Fetch author dari tabel users dengan author_id
                            $author_id = $post['author_id'];
                            $author_query = "SELECT * FROM users WHERE id=$author_id";
                            $author_result = mysqli_query($connection, $author_query);
                            $author = mysqli_fetch_assoc($author_result);
                            ?>
                            <div class="post_author-avatar">
                                <img src="images/<?= ($author['avatar']) ?>" alt="<?= ($author['firstname'] . ' ' . $author['lastname']) ?>" />
                            </div>
                            <div class="post_author-info">
                                <h5><?= ("{$author['firstname']} {$author['lastname']}") ?></h5>
                                <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
<?php else : ?>
    <div class="alert_message error lg section_extra-margin">
        No Posts found for this search.
    </div>

<?php endif; ?>
<!-- End Post -->

<?php include 'partials/footer.php' ?>