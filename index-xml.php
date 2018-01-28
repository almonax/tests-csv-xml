<?php

/**
 * Reading XML with SimpleXMLElement
 */

$xmlFile = file_get_contents('xml.xml');

$simpleXml = new SimpleXMLElement($xmlFile);

echo "<pre>";

$i = 0;
while ($i < count($simpleXml->CURRENCY)) {
    echo "\nCURRENCY[$i]";

    echo "\n\t NAME:" . $simpleXml->CURRENCY[$i]->NAME;
    echo "\n\t UNIT:" . $simpleXml->CURRENCY[$i]->UNIT;
    echo "\n\t COUNTRY:" . $simpleXml->CURRENCY[$i]->COUNTRY;
    echo "\n\t CURRENCYCODE:" . $simpleXml->CURRENCY[$i]->CURRENCYCODE;
    echo "\n\t RATE:" . $simpleXml->CURRENCY[$i]->RATE;
    echo "\n\t CHANGE:" . $simpleXml->CURRENCY[$i]->CHANGE;
    echo "\n";

    $i++;
}

echo "</pre>";


/**
 * Writing XML with XMLWriter
 */

$xmlFile = 'new-xml.xml';

$xmlWriter = new XMLWriter();
$xmlWriter->openMemory();
$xmlWriter->startDocument('1.0', 'UTF-8');
$xmlWriter->startElement('CURRENCIES');
$xmlWriter->writeElement('LAST_UPDATE', date('Y-m-d'));

foreach ($simpleXml->CURRENCY as $item) {
    $xmlWriter->startElement('CURRENCY');

    $xmlWriter->writeElement('NAME', $item->NAME);
    $xmlWriter->writeElement('UNIT', $item->UNIT);
    $xmlWriter->writeElement('COUNTRY', $item->COUNTRY);
    $xmlWriter->writeElement('CURRENCYCODE', $item->CURRENCYCODE);
    $xmlWriter->writeElement('RATE', $item->RATE);
    $xmlWriter->writeElement('CHANGE', $item->CHANGE);

    $xmlWriter->endElement();
}
$xmlWriter->endDocument();
$writeToFile = fopen($xmlFile, 'w');
fwrite($writeToFile, $xmlWriter->outputMemory());
fclose($writeToFile);

echo '<hr>';


/**
 * Reading XML with XMLReader
 */

$xmlReader = new XMLReader();
$xmlReader->open($xmlFile);

echo '<pre>';

while ($xmlReader->read() && $xmlReader->name !== 'LAST_UPDATE');
$node = new SimpleXMLElement($xmlReader->readOuterXml());
var_dump($node);

echo '<hr>';

while($xmlReader->read() && $xmlReader->name !== 'CURRENCY');

while($xmlReader->name === 'CURRENCY')
{
    $node = new SimpleXMLElement($xmlReader->readOuterXML());
    var_dump($node);
    echo '<hr>';

    $xmlReader->next('CURRENCY');
}
$xmlReader->close();
echo '</pre>';


/**
 * Writing XML with SimpleXMLElement
 */

$simpleXml = new SimpleXMLElement(file_get_contents($xmlFile));
$newNode = $simpleXml->addChild('CURRENCY');

$newNode->addChild('NAME', 'Yuán');
$newNode->addChild('UNIT', '1');
$newNode->addChild('COUNTRY', 'China');
$newNode->addChild('CURRENCYCODE', 'CNY');
$newNode->addChild('RATE', '41.22');
$newNode->addChild('CHANGE', '1.14');

$writeToFile = fopen('new-xml-1.xml', 'w');
fwrite($writeToFile,  $simpleXml->asXML());
fclose($writeToFile);