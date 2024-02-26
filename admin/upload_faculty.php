<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

include "sidebar.php";
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
                        // Assuming password is in the 6th column (index 5)
                        $hashedPassword = password_hash($spreadSheetAry[$i][6], PASSWORD_DEFAULT);

                        $stmt = $conn->prepare("INSERT INTO faculty (username, name, section, dept, email, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $spreadSheetAry[$i][0], $spreadSheetAry[$i][1], $spreadSheetAry[$i][2], $spreadSheetAry[$i][3], $spreadSheetAry[$i][4], $spreadSheetAry[$i][5], $hashedPassword);
                        $stmt->execute();
                    }

                    $stmt->close();
                } else {
                    echo '<script>alert("The file does not exist..");</script>';
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
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header text-md-left">
                <h3>Faculty Upload</h3>

                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <h2>Import Faculty Data In Excel</h2>
                            <p>Supported extensions .xlsx and .xls</p>
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3 mt-3">
                                    <label for="email">Upload Excel:</label>
                                    <input type="file" class="form-control" name="exceldata">
                                </div>

                                <button type="submit" name="import" class="btn btn-primary">Upload</button>

                                <hr>
                            <div card-footer>
                                <p>Download the template to upload the Data.</p>
                                     <!-- Download button for Excel template -->
                                    <button type="button" onclick="downloadExcelTemplate()" class="btn btn-secondary">Download Excel Template</button>
                            </div>



                            </form>
                            <br><br><br>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
function downloadExcelTemplate() {
    // Replace 'template.xlsx' with the actual filename of your Excel template
    var downloadLink = document.createElement('a');
    downloadLink.href = 'downloads/faculty-upload.xlsx';
    downloadLink.download = 'faculty.xlsx';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

<?php 
include "footer.html";
?>
