<?php
use MED\Kassa\Service\CryptoService;

require_once __DIR__ . '/../vendor/autoload.php';

define('DEFAULT_AMOUNT', 100);
define('DEFAULT_LENGTH', 12);

if (isset($argv[1])) {
    $amount = (int) $argv[1];
} else {
    $amount = DEFAULT_AMOUNT;
}

if (isset($argv[2])) {
    $length = (int) $argv[2];
} else {
    $length = DEFAULT_LENGTH;
}

for($i = 0; $i < $amount; $i++) {
    $val = CryptoService::createAESKey();
    $key = CryptoService::extractBytesFromHashAsBase64(
        CryptoService::generateHash($val, 'sha256')
        , 3
    );
    echo $key . '|' . $val  .'|'. strlen($val) . "\n\r";
}