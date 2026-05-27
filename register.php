<?php
include 'config.php';

$message = "";

if(isset($_POST['register']))
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password))
    {
        $message = "All fields are required!";
    }
    elseif(strlen($password) < 6)
    {
        $message = "Password must be at least 6 characters!";
    }
    else
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(username, password) VALUES(?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

        if(mysqli_stmt_execute($stmt))
        {
            $message = "Registration successful!";
        }
        else
        {
            $message = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <h2>Register</h2>

    <p><?php echo $message; ?></p>

    <form method="POST">

        <input type="text" name="username" placeholder="Enter Username">

        <input type="password" name="password" placeholder="Enter Password">

        <button type="submit" name="register">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

</body>
</html>