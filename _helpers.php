<?php

/**
 * Helper functions
 */

/**
 * Create tag
 *
 * @param $tag
 * @param $content
 * @return string
 */
function tag($tag, $content) {
    return "<$tag>$content</$tag>";
}

/**
 * Update param
 *
 * @param $dataArray
 * @param $elementName
 * @param $currentValue
 * @param string $currencyName
 * @return false|string
 */
function updateParams($dataArray, $elementName, $currentValue, $currencyName = '') {
    if (!empty($currencyName) && isset($dataArray['currencies'][$currencyName])) {
        $data = $dataArray['currencies'][$currencyName];
        foreach ($data as $name => $value) {
            if ($elementName === strtoupper($name)) {
                $currentValue = $value;
            }
        }
    } elseif ($elementName === 'LAST_UPDATE') {
        $currentValue = (isset($dataArray['lastUpdate']))
            ? $dataArray['lastUpdate']
            : date('Y-m-d');
    }

    return $currentValue;
}

/**
 * Create html element table row
 *
 * @param $array
 * @param string $cell
 * @return string
 */
function createTableRow($array, $cell = 'td') {
    $row = '';
    foreach ($array as $item) {
        $row .= tag($cell, $item);
    }
    return tag('tr', $row);
}