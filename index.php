
<?php
require_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);

$stmt = $pdo->query(
    'SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC'
);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexPlanet Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="index.php">ApexPlanet Blog</a>

            <nav>
                <?php if ($isLoggedIn): ?>
                    <span>Welcome, <?= e($_SESSION['username'] ?? 'User') ?></span>
                    <a class="button-link" href="create.php">New Post</a>

                    <form class="inline-form" method="POST" action="logout.php">
                        <input type="hidden" name="csrf_token"
                               value="<?= e(csrf_token()) ?>">
                        <button type="submit" class="secondary-button">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a class="button-link" href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <p class="eyebrow">SHARE YOUR IDEAS</p>
            <h1>Welcome to the Blog</h1>
            <p>Create, read, update and delete blog posts in one place.</p>

            <?php if (!$isLoggedIn): ?>
                <a class="button-link" href="register.php">Get Started</a>
            <?php endif; ?>
        </section>

        <section class="posts-section">
            <h2>Latest Posts</h2>

            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <h3>No posts yet</h3>
                    <p>
                        <?php if ($isLoggedIn): ?>
                            Create your first blog post to get started.
                        <?php else: ?>
                            Register or log in to publish your first post.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <p class="post-date">
                                <?= e($post['created_at']) ?>
                            </p>

                            <h3><?= e($post['title']) ?></h3>

                            <div class="post-content"><?= nl2br(e($post['content'])) ?></div>

                            <?php if ($isLoggedIn): ?>
                                <div class="post-actions">
                                    <a class="button-link"
                                       href="edit.php?id=<?= (int) $post['id'] ?>">
                                        Edit
                                    </a>

                                    <form method="POST" action="delete.php"
                                          onsubmit="return confirm('Delete this post?');">
                                        <input type="hidden" name="csrf_token"
                                               value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="id"
                                               value="<?= (int) $post['id'] ?>">
                                        <button type="submit" class="danger-button">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>Basic CRUD Application · ApexPlanet Internship Task 2</p>
    </footer>
</body>
</html>
