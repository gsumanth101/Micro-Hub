<?php
include('sidebar.php');
$sql = "SELECT * FROM problem_statement";
$result = $conn->query($sql);

$problems = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $problems[$row["problem_id"]] = $row["problem_statement"];
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Selection</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Select a Problem:</h2>
        <form action="" method="post">
            <div class="form-group">
                <select class="form-control" name="problem_id" id="problem_id">
                    <option value="">Select Problem</option>
                    <?php
                    foreach ($problems as $id => $statement) {
                        echo "<option value='$id'>$id</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="problem_statement" id="problem_statement" rows="5" cols="50" disabled></textarea>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to fetch problem statement based on selected problem_id
        document.getElementById('problem_id').addEventListener('change', function() {
            var selectedId = this.value;
            var statements = <?php echo json_encode($problems); ?>;
            document.getElementById('problem_statement').value = statements[selectedId];
        });
    </script>
</body>
</html>

<?php 
include('footer.html');
?>



                    <!-- /.card -->
                    <div class="card card-info" style="float: right;">
                        <div class="card-header">
                            <h3 class="card-title">Team Details</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Team Members</th>
                                        <th>Regd. Numbers</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td><?php echo $row1["mem_name1"] ?></td>
                                        <td><?php echo $row1["mem_reg1"] ?></td>
                                    <tr>
                                        <td><?php echo $row1["mem_name2"] ?></td>
                                        <td><?php echo $row1["mem_reg2"] ?></td>
                                    <tr>
                                        <td><?php echo $row1["mem_name3"] ?></td>
                                        <td><?php echo $row1["mem_reg3"] ?></td>
                                    <tr>
                                        <td><?php echo $row1["mem_name4"] ?></td>
                                        <td><?php echo $row1["mem_reg4"] ?></td>
                                    <tr>
                                        <td><?php echo $row1["mem_name5"] ?></td>
                                        <td><?php echo $row1["mem_reg5"] ?></td>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    </div>
                    <!-- /.card -->
