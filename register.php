<?php
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // SERVER SIDE VALIDATION

    if (empty($username) || empty($password)) {
        $message = "All fields are required";
    }
    elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    }
    else {

        // HASH PASSWORD

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // PREPARED STATEMENT

        $stmt = $conn->prepare("INSERT INTO users(username, password) VALUES (?, ?)");

        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registration successful";
        } else {
            $message = "Error occurred";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<p><?php echo $message; ?></p>

<form method="POST">

    Username:
    <input type="text" name="username" required>
    <br><br>

    Password:
    <input type="password" name="password" required minlength="6">
    <br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>