<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add some data to the spreadsheet
$sheet->setCellValue('A1', 'Hello');
$sheet->setCellValue('B1', 'World!');

// Save the spreadsheet to a file
$writer = new Xlsx($spreadsheet);

// Output to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="test_spreadsheet.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
