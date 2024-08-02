<?php
include('../includes/connection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require '../vendor/autoload.php';
$serial_number_start = 1;
// Function to export data to Excel
function exportToExcel($data, $filename) {
    // Create a new Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Add a worksheet
    $worksheet = $spreadsheet->getActiveSheet();

    // Set column headers
    $worksheet->setCellValue('A1', 'S.No');
    $worksheet->setCellValue('B1', 'Team ID');
    $worksheet->setCellValue('C1', 'Team Name');
    $worksheet->setCellValue('D1', 'Team Leader Regd.No');
    $worksheet->setCellValue('E1', 'Team Leader Name');
    $worksheet->setCellValue('F1', 'Problem ID');
    $worksheet->setCellValue('G1', 'Problem Statement');

    // Fetch and populate data
    $rowNumber = 2; // Start from the second row
    foreach ($data as $row) {
        $worksheet->setCellValue('A' . $rowNumber, $row['serial_number']);
        $worksheet->setCellValue('B' . $rowNumber, $row['team_id']);
        $worksheet->setCellValue('C' . $rowNumber, $row['team_name']);
        $worksheet->setCellValue('D' . $rowNumber, $row['mem_reg1']);
        $worksheet->setCellValue('E' . $rowNumber, $row['mem_name1']);
        $worksheet->setCellValue('F' . $rowNumber, $row['problem_id']);
        $worksheet->setCellValue('G' . $rowNumber, $row['problem_statement']);


        $rowNumber++;
    }

    // Save the Excel file
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
}

// Handle download button click
if (isset($_POST['download'])) {
    // Fetch all data without pagination for export
    $export_sql = "SELECT ta.serial_number, ta.team_id, it.team_lead_regd, it.team_lead_name, ta.problem_id, ta.problem_statement
                   FROM team_assignments ta
                   INNER JOIN internship_teams it ON ta.team_id = it.team_id";
                   
    $export_result = $conn->query($export_sql);

    $export_data = [];
    while ($export_row = $export_result->fetch_assoc()) {
        $export_row['serial_number'] = $serial_number_start++;
        $export_data[] = $export_row;
    }

    // Export data to Excel
    $excelFileName = 'micro_project_Accepted.xlsx';
    exportToExcel($export_data, $excelFileName);

    // Provide the Excel file for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
    header('Cache-Control: max-age=0');

    // Clean output buffer and send file content
    ob_clean();
    readfile($excelFileName);

    // Delete the temporary Excel file
    unlink($excelFileName);

    // End the script
    exit();
}

$conn->close();
?>
