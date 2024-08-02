<?php
include('sidebar.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_limit = $_POST['new_limit'];

    $stmt = $conn->prepare("UPDATE problem_statement SET global_limit = ?");
    $stmt->bind_param("i", $new_limit);
    if ($stmt->execute()) {
        echo "Global limit updated!";
    } else {
        echo "Error updating global limit: " . mysqli_error($conn);
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <form method="POST" action="intern_limit.php">
        <label>New Global Limit:</label>
        <input type="number" name="new_limit" required><br>
        <button type="submit" name='submit'>Update Global Limit</button>
    </form>
</body>
</html>
<?php
include('footer.html');
?>
