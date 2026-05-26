<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM posts ORDER BY id DESC");
?>

<h2>Welcome <?php echo $_SESSION['username']; ?></h2>

<p>Role: <?php echo $_SESSION['role']; ?></p>

<a href="create_post.php">Create Post</a>
<a href="logout.php">Logout</a>

<hr>

<?php while($row = $result->fetch_assoc()) { ?>

<h3><?php echo $row['title']; ?></h3>

<p><?php echo $row['content']; ?></p>

<a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a>

<?php if($_SESSION['role'] == 'admin') { ?>

<a href="delete_post.php?id=<?php echo $row['id']; ?>">
Delete
</a>

<?php } ?>

<hr>

<?php } ?>