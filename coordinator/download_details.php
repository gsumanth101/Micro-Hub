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
    $worksheet->setCellValue('B1', 'Regd Number');
    $worksheet->setCellValue('C1', 'Name');
    $worksheet->setCellValue('D1', 'Tool Name');
    $worksheet->setCellValue('E1', 'Mentor Name');
    $worksheet->setCellValue('F1', 'Year');
    $worksheet->setCellValue('G1', 'Status');
    $worksheet->setCellValue('H1', 'Evaluation Status');

    // Fetch and populate data
    $rowNumber = 2; // Start from the second row
    foreach ($data as $row) {
        $worksheet->setCellValue('A' . $rowNumber, $row['serial_number']);
        $worksheet->setCellValue('B' . $rowNumber, $row['username']);
        $worksheet->setCellValue('C' . $rowNumber, $row['name']);
        $worksheet->setCellValue('D' . $rowNumber, $row['tool_name']);
        $worksheet->setCellValue('E' . $rowNumber, $row['mentor_name']);
        $worksheet->setCellValue('F' . $rowNumber, $row['year']);
        $worksheet->setCellValue('G' . $rowNumber, $row['acceptance_status']);
        $worksheet->setCellValue('H' . $rowNumber, $row['evaluation_status']);

        $rowNumber++;
    }

    // Save the Excel file
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
}

// Handle download button click
if (isset($_POST['download'])) {
    // Fetch all data without pagination for export
    $export_sql = "SELECT * FROM micro_project $search_condition_sql";
    $export_result = $conn->query($export_sql);

    $export_data = [];
    while ($export_row = $export_result->fetch_assoc()) {
        $export_row['serial_number'] = $serial_number_start++;
        $export_data[] = $export_row;
    }

    // Export data to Excel
    $excelFileName = 'micro_project_Detials.xlsx';
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
