<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $update = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");

    $update->bind_param("ssi", $title, $content, $id);

    $update->execute();

    header("Location: view_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit</title>
</head>
<body>

<h2>Edit Post</h2>

<form method="POST">

    Title:
    <input type="text" name="title"
    value="<?php echo $post['title']; ?>" required>

    <br><br>

    Content:
    <textarea name="content" required><?php echo $post['content']; ?></textarea>

    <br><br>

    <button type="submit">Update</button>

</form>

</body>
</html>