<?php
include 'db.php';

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

<div class="container">

<h1>Welcome <?php echo $_SESSION['username']; ?></h1>

<hr>

<a href="create_post.php">Create New Post</a>

<br><br>

<a href="view_posts.php">View All Posts</a>

<br><br>

<a href="logout.php">Logout</a>

</div>

</body>
</html>