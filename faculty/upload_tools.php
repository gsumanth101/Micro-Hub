<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

include "micro_sidebar.php";
require_once '../vendor/autoload.php';

// Create the "uploads" directory if it doesn't exist
$uploadDirectory = 'uploads';
if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

if (isset($_POST['import'])) {
    if (isset($_FILES['exceldata'])) {
        $allowedFileTypes = [
            'application/vnd.ms-excel',
            'text/xls',
            'text/xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        $fileType = mime_content_type($_FILES['exceldata']['tmp_name']);

        if (in_array($fileType, $allowedFileTypes)) {
            $filename = $_FILES['exceldata']['name'];
            $tempname = $_FILES['exceldata']['tmp_name'];
            $destination = 'uploads/' . $filename;

            if (move_uploaded_file($tempname, $destination)) {
                if (file_exists($destination)) {
                    $reader = new Xlsx();
                    $spreadSheet = $reader->load($destination);
                    $excelSheet = $spreadSheet->getActiveSheet();
                    $spreadSheetAry = $excelSheet->toArray();
                    $sheetCount = count($spreadSheetAry);

                    for ($i = 1; $i < $sheetCount; $i++) {
                        $stmt = $conn->prepare("INSERT INTO microproject_tools (id, toolname, maxlimit) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $spreadSheetAry[$i][0], $spreadSheetAry[$i][1], $spreadSheetAry[$i][2]);
                        $stmt->execute();
                    }

                    $stmt->close();

                    // Display success alert
                    echo '<script>alert("Excel data imported successfully.");</script>';
                } else {
                    echo '<script>alert("The file does not exist.");</script>';
                }
            } else {
                echo '<script>alert("Failed to move the uploaded file.");</script>';
            }
        } else {
            echo '<script>alert("Invalid file type. Please upload a valid Excel file.");</script>';
        }
    } else {
        echo '<script>alert("No file uploaded.");</script>';
    }
} else {
    // Uncomment the line below if you want to show an alert when the page is loaded
    // echo '<script>alert("Please upload an Excel file with a valid extension.");</script>';
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <!-- Header content -->
            </div><!-- /.container-fluid -->
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Tools Upload</h3>
                <div class="card-tools">
                    <!-- Card tools content -->
                </div>
            </div>
            <div class="card-body">
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3 mt-3">
                                    <label for="email">Upload Excel:</label>
                                    <input type="file" class="form-control" name="exceldata">
                                </div>
                                <button type="submit" name="import" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include "footer.html";
?>
