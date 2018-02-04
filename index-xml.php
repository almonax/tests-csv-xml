<?php
include '_header.php';
require '_helpers.php';
?>

<h2>Reading XML with SimpleXMLElement</h2>

<?php
/**
 * Reading XML with SimpleXMLElement
 */
$xmlFile = file_get_contents('xml.xml');
$simpleXml = new SimpleXMLElement($xmlFile);

?>

<ul>
    <li>Last update <?= $simpleXml->LAST_UPDATE ?></li>
    <li>Currencies
        <ul>
            <?php foreach ($simpleXml->CURRENCY as $currency): ?>
            <li><?= $currency->NAME ?>
                <ul>
                    <li>Unit:           <?= $currency->UNIT ?></li>
                    <li>Country:        <?= $currency->COUNTRY ?></li>
                    <li>Currency code:  <?= $currency->CURRENCYCODE ?></li>
                    <li>Rate:           <?= $currency->RATE ?></li>
                    <li>Change:         <?= $currency->CHANGE ?></li>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>

<h2>Write XML with XMLWriter and reading with XMLReader</h2>

<?php
/**
 * Write XML with XMLWriter and reading with XMLReader
 */

$newData = require '_newData.php';
$xmlFile = 'new-xml.xml';

$xmlReader = new XMLReader();
$xmlReader->open('xml.xml');

$xmlWriter = new XMLWriter();
$xmlWriter->openMemory();
$xmlWriter->startDocument('1.0', 'UTF-8');

$element = '';
$currencyName = '';
$content = '';
$isTreeOpened = false;
while ($xmlReader->read()) {
    switch ($xmlReader->nodeType) {
        case XMLReader::ELEMENT:
            $element = $xmlReader->name;
            $xmlWriter->startElement($xmlReader->name);
            break;
        case XMLReader::END_ELEMENT:

            // print block
            if ($element === 'LAST_UPDATE') {
                $content .= '<li>Currencies<ul>';
            } elseif ($element === 'CURRENCY') {
                $content .= '</ul>';
            }
            // end print

            $element = '';
            $xmlWriter->endElement();

            break;
        case XMLReader::TEXT:
            $value = $xmlReader->value;
            if ($element === 'NAME') {
                $currencyName = $xmlReader->value;
            } else {
                $value = updateParams(
                        $newData,
                        $element,
                        $value,
                        $currencyName
                );
            }
            $xmlWriter->writeRaw($value);

            // print block
            $elemCopy = $element;
            if ($element === 'NAME') {
                if ($isTreeOpened) {
                    $content .= '</ul></li>';
                }
                $content .= "<li>$value<ul>";
                $isTreeOpened = true;
            } else {
                if ($element === 'CURRENCYCODE') {
                    $elemCopy = str_replace('CODE', ' CODE', $elemCopy);
                }
                $content .= tag('li', ucfirst(strtolower(str_replace('_', ' ', $elemCopy))) . ': ' . $value);
            }
            // end print

            break;
    }
}

$xmlReader->close();
$xmlWriter->endDocument();
file_put_contents($xmlFile, $xmlWriter->outputMemory());

/**
 * Print update result
 */
echo tag('ul', $content);

include '_footer.php';