<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // VALIDATION

    if (empty($title) || empty($content)) {
        $message = "All fields required";
    }
    else {

        // PREPARED STATEMENT

        $stmt = $conn->prepare("INSERT INTO posts(title, content) VALUES (?, ?)");

        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            $message = "Post created successfully";
        } else {
            $message = "Error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>
<?php include 'navbar.php'; ?>
<h2>Create Post</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    Title:
    <input type="text" name="title" required>
    <br><br>

    Content:
    <textarea name="content" required></textarea>
    <br><br>

    <button type="submit">Create</button>

</form>

</body>
</html>