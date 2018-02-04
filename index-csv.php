<?php
include '_header.php';
require '_helpers.php';

$newData = require '_newData.php';
$csvFile = fopen('csv.csv', 'r');
$newCsvFile = fopen('new-csv.csv', 'w');

/**
 * Update date and write new csv file
 */
$tables = [
    'csv' => '',
    'new-csv' => ''
];
$tableHeader = [];
$isNewTable = true;
$endTable = '</tbody></table>';
while (($line = fgetcsv($csvFile, 0, ';')) !== false) {
    if ($isNewTable) {
        $tableHeader = $line;
        $startTable = '<table class="table">' . tag('thead', createTableRow($line, 'th')) . '<tbody>';
        $tables['csv'] .= $startTable;
        $tables['new-csv'] .= $startTable;
        $isNewTable = false;
    } elseif (current($line) === null) {
        $tables['csv'] .= $endTable;
        $tables['new-csv'] .= $endTable;
        $isNewTable = true;
    } else {
        $tables['csv'] .= createTableRow($line);
        for ($i = 0; $i < count($line); $i++) {
            $line[$i] = updateParams(
                    $newData,
                    $tableHeader[$i],
                    $line[$i],
                    $line[(int) array_search('NAME', $tableHeader)]
                );
        }
        $tables['new-csv'] .= createTableRow($line);
    }

    fputcsv($newCsvFile, $line, ';');
}
$tables['csv'] .= $endTable;
$tables['new-csv'] .= $endTable;

fclose($csvFile);
fclose($newCsvFile);
?>

<div class="row">
    <h2>Data from <code>csv.csv</code> file</h2>
    <?= $tables['csv'] ?>
</div>

<div class="row">
    <h2>Data from <code>new-csv.csv</code> file</h2>
    <?= $tables['new-csv'] ?>
</div>
<?php
include '_footer.php';

// Записать CSV с сегодняшней датой и обновленными и курсами валют
// Вывести в удобочитаемом виде