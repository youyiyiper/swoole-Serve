<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'app\\' => array($baseDir . '/app'),
    'Serve\\' => array($baseDir . '/lib/src'),
    'Predis\\' => array($vendorDir . '/predis/predis/src'),
    'Medoo\\' => array($vendorDir . '/catfan/medoo/src'),
    'Delayer\\' => array($vendorDir . '/mix/delayer-client/src'),
);
