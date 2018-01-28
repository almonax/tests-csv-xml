<?php
// Require CSV class
require_once 'CSV.php';

// Create CSV class
$csv = new CSV('csv.csv');
// Get all data
$csvContent = $csv->get();

// Set data into new file
$csv->setFile('new-csv.csv');
$csv->set($csvContent);

// change mode
$csv->setMode(CSV::MODE_LINE);
$csvContent = [];

// Get data on one line
while (($getLine = $csv->get()) !== false) {
    $csvContent[] = $getLine;
}

// Set data on one line
$csv->setFile('new-csv-1.csv');
foreach ($csvContent as $item) {
    $csv->set($item);
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test CSV</title>
</head>
<body>

    <pre><?php var_dump($csvContent); ?></pre>

</body>
</html>
