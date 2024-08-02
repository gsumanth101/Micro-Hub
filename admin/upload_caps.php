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
                        $id = (int)$spreadSheetAry[$i][0];
                        $team_id = $spreadSheetAry[$i][1];
                        $team_name = $spreadSheetAry[$i][2];
                        $mem_regd1 = $spreadSheetAry[$i][3];
                        $mem_name1 = $spreadSheetAry[$i][4];
                        $mem_regd2 = $spreadSheetAry[$i][5];
                        $mem_name2 = $spreadSheetAry[$i][6];
                        $mem_regd3 = $spreadSheetAry[$i][7];
                        $mem_name3 = $spreadSheetAry[$i][8];
                        $mem_regd4 = $spreadSheetAry[$i][9];
                        $mem_name4 = $spreadSheetAry[$i][10];
                        $guide_name = $spreadSheetAry[$i][11];
                        $guide_username = $spreadSheetAry[$i][12];
                        $project_title = $spreadSheetAry[$i][13];
                        
                    
                        $stmt = $conn->prepare("INSERT INTO caps_teams (id, team_id, team_name, mem_regd1, mem_name1, mem_regd2, mem_name2, mem_regd3, mem_name3, mem_regd4, mem_name4, guide_name, guide_username, project_title) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                        if ($stmt === false) {
                            die('Error preparing query: ' . $conn->error);
                        }
                    
                        $stmt->bind_param("isssssssssssss", $id, $team_id, $team_name, $mem_regd1, $mem_name1, $mem_regd2, $mem_name2, $mem_regd3, $mem_name3, $mem_regd4, $mem_name4, $guide_name, $guide_username, $project_title);
                    
                        if ($stmt->execute() === false) {
                            die('Error executing query: ' . $stmt->error);
                        }
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


    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Internship Upload</h3>

                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
            <div class="container mt-3">
            <div class="row justify-content-center">
            <div class="col-xl-6">
            <h2>Import Capston Data In Excel</h2>
            <p>Supported extensions .xlsx and .xls</p>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                <label for="email">Upload Excel:</label>
                <input type="file" class="form-control" name="exceldata">
                </div>
            
                <button type="submit" name="import" class="btn btn-primary">Upload</button>
                <hr>
               <!-- <div card-footer>
                    <p>Download the template to upload the Data.</p>
                     
                    <button type="button" onclick="downloadExcelTemplate()" class="btn btn-secondary">Download Excel Template</button>
                </div>-->

                
            </form>


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


<?php 
include "footer.html";
?>