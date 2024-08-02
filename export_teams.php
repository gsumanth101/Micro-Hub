<?php
include "includes/connection.php";
require 'vendor/autoload.php'; // Load PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



// Fetch data
$sql = "SELECT team_id, team_name, guide_name, 
               mem_regd1, mem_regd2, mem_regd3, mem_regd4,
               mem_name1, mem_name2, mem_name3, mem_name4 
        FROM caps_teams";
$result = $conn->query($sql);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Your Name')
    ->setLastModifiedBy('Your Name')
    ->setTitle('Teams Registration')
    ->setSubject('Teams Registration')
    ->setDescription('Generated Excel file for teams registration')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Result file');

// Add some data
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Team ID')
    ->setCellValue('B1', 'Team Name')
    ->setCellValue('C1', 'Guide Name')
    ->setCellValue('D1', 'Registration Numbers')
    ->setCellValue('E1', 'Names');

// Fetch and output data
$rowNum = 2;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $startRow = $rowNum;
        for ($i = 1; $i <= 4; $i++) {
            if (!is_null($row["mem_regd$i"]) && !is_null($row["mem_name$i"])) {
                if ($rowNum == $startRow) {
                    $sheet->setCellValue('A' . $rowNum, $row['team_id'])
                          ->setCellValue('B' . $rowNum, $row['team_name'])
                          ->setCellValue('C' . $rowNum, $row['guide_name']);
                }
                $sheet->setCellValue('D' . $rowNum, $row["mem_regd$i"])
                      ->setCellValue('E' . $rowNum, $row["mem_name$i"]);
                $rowNum++;
            }
        }
        // Merge cells for Team ID, Team Name, and Guide Name
        if ($startRow < $rowNum) {
            $sheet->mergeCells("A{$startRow}:A" . ($rowNum - 1));
            $sheet->mergeCells("B{$startRow}:B" . ($rowNum - 1));
            $sheet->mergeCells("C{$startRow}:C" . ($rowNum - 1));
        }
    }
}

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Teams Registration');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="teams_registration.xlsx"');
header('Cache-Control: max-age=0');

// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

// Close database connection
$conn->close();
?>
