<?php
if (isset($_POST['submit'])) {
    $mem_att1 = $_POST['mem_att1'];
    $mem_att2 = $_POST['mem_att2'];
    $mem_att3 = $_POST['mem_att3'];
    $mem_att4 = $_POST['mem_att4'];
    $mem_att5 = $_POST['mem_att5'];

    $sql = "UPDATE internship_teams SET mem_att1 = '$mem_att1', mem_att2 = '$mem_att2', mem_att3 = '$mem_att3', mem_att4 = '$mem_att4', mem_att5 = '$mem_att5' WHERE team_id = '$team_id'";
    $result = $conn->query($sql);
    if ($result) {
        echo '<script>alert("Attendance Submitted Successfully")</script>';
    } else {
        echo '<script>alert("Attendance Submission Failed")</script>';
    }
}
