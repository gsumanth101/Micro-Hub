<?php
include('../includes/connection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_id = $_POST['team_id'];

    // Prepare the SQL statement to remove guide_name and guide_username
    $sql = "UPDATE caps_teams SET guide_name = NULL, guide_username = NULL WHERE team_id = ?";

    // Initialize the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the variables to the prepared statement as parameters
        $stmt->bind_param("s", $team_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }

        // Close the statement
        $stmt->close();
    } else {
        echo 'error';
    }

    // Close the connection
    $conn->close();
}
?>