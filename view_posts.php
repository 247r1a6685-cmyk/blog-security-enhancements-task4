<?php
session_start();

if(!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

include 'config.php';

/* Pagination */
$limit = 5;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$start = ($page - 1) * $limit;

/* Search */
$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);

    $sql = "SELECT * FROM posts 
            WHERE title LIKE ? 
            OR content LIKE ?
            ORDER BY created_at DESC
            LIMIT ?, ?";

    $stmt = mysqli_prepare($conn, $sql);

    $search_term = "%$search%";

    mysqli_stmt_bind_param($stmt, "ssii", $search_term, $search_term, $start, $limit);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    /* Count Search Results */
    $count_sql = "SELECT COUNT(*) as total 
                  FROM posts 
                  WHERE title LIKE ? 
                  OR content LIKE ?";

    $count_stmt = mysqli_prepare($conn, $count_sql);

    mysqli_stmt_bind_param($count_stmt, "ss", $search_term, $search_term);

    mysqli_stmt_execute($count_stmt);

    $count_result = mysqli_stmt_get_result($count_stmt);

    $total_records = mysqli_fetch_assoc($count_result)['total'];
}
else
{
    $sql = "SELECT * FROM posts 
            ORDER BY created_at DESC 
            LIMIT ?, ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ii", $start, $limit);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    /* Count Total Records */
    $count_query = "SELECT COUNT(*) as total FROM posts";

    $count_result = mysqli_query($conn, $count_query);

    $total_records = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html>
<head>

    <title>View Posts</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <h2>All Posts</h2>

    <!-- Search Form -->
    <form method="GET">

        <input type="text" 
               name="search" 
               placeholder="Search posts..."
               value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">Search</button>

    </form>

    <br>

<?php
if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
?>

        <h3>
            <?php echo htmlspecialchars($row['title']); ?>
        </h3>

        <p>
            <?php echo htmlspecialchars($row['content']); ?>
        </p>

        <small>
            <?php echo $row['created_at']; ?>
        </small>

        <br><br>

        <a href="edit_post.php?id=<?php echo $row['id']; ?>">
            Edit
        </a>

        |

        <a href="delete_post.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure?');">
            Delete
        </a>

        <hr>

<?php
    }
}
else
{
    echo "<p>No posts found!</p>";
}
?>

<!-- Pagination -->

<div>

<?php
for($i = 1; $i <= $total_pages; $i++)
{
?>

    <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
        <?php echo $i; ?>
    </a>

<?php
}
?>

</div>

</div>

</body>
</html>