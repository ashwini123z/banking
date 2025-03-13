<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Sample HTML content with a table
$htmlContent = '
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Age</th>
      <th>City</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Alice</td>
      <td>30</td>
      <td>New York</td>
    </tr>
    <tr>
      <td>Bob</td>
      <td>25</td>
      <td>Los Angeles</td>
    </tr>
    <tr>
      <td>Charlie</td>
      <td>35</td>
      <td>Chicago</td>
    </tr>
  </tbody>
</table>';

// Load the HTML content into a DOMDocument object
$dom = new DOMDocument();
@$dom->loadHTML($htmlContent); // Suppress errors with @

// Find the table in the DOM
$table = $dom->getElementsByTagName('table')->item(0);
$rows = $table->getElementsByTagName('tr');

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Loop through the table rows and cells
$rowIndex = 1;
foreach ($rows as $row) {
    $colIndex = 1;
    $cells = $row->getElementsByTagName('th');
    if ($cells->length === 0) {
        $cells = $row->getElementsByTagName('td');
    }

    foreach ($cells as $cell) {
        // Convert column index to Excel column letter (e.g., 1 -> A, 2 -> B)
        $colLetter = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colLetter . $rowIndex, $cell->textContent);
        $colIndex++;
    }
    $rowIndex++;
}

// Write the spreadsheet to an Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('output.xlsx');

echo "HTML table has been successfully converted to Excel.";
?>
