<?php
include "sidebar.php";
$sql = "SELECT * FROM event_registration";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM event_submission";
$result1 = $conn->query($sql1);

$conn->close();
?>

<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-black">
                <h3 class="card-title">Registrations </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body bg-light">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["event_name"] . "</td>";
                                    $status="";
                                    if ($row["registration_enabled"]==1)
                                    {
                                        $status = "Enabled";
                                    }
                                    else
                                    {
                                        $status = "Disabled";
                                    }
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
    
                                }

                            }
                             else {

                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="card">
            <div class="card-header bg-black">
                <h3 class="card-title">Submissions</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result1->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result1->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["event_name"] . "</td>";
                                    $status="";
                                    if ($row["submission_enabled"]==1)
                                    {
                                        $status = "Enabled";
                                    }
                                    else
                                    {
                                        $status = "Disabled";
                                    }
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
    
                                }

                            }
                             else {

                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include "footer.html";
?>
