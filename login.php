<?php
session_start();

include 'config.php';

$message = "";

if(isset($_POST['login']))
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password))
    {
        $message = "All fields are required!";
    }
    else
    {
        $sql = "SELECT * FROM users WHERE username=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result);

            if(password_verify($password, $row['password']))
            {
                $_SESSION['username'] = $username;

                header("Location: dashboard.php");
                exit();
            }
            else
            {
                $message = "Invalid password!";
            }
        }
        else
        {
            $message = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <h2>Login</h2>

    <p><?php echo $message; ?></p>

    <form method="POST">

        <input type="text" name="username" placeholder="Enter Username">

        <input type="password" name="password" placeholder="Enter Password">

        <button type="submit" name="login">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>