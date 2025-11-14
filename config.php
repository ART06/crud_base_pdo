<?php
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'minimundos';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$TABLE = 'coleta_seletiva';
$COLUMNS = [
  ['name' => 'bairro', 'label' => 'Bairro', 'type' => 'text'],
  ['name' => 'tipo_residuo', 'label' => 'Tipo de Resíduo', 'type' => 'text'],
  ['name' => 'peso_total', 'label' => 'Peso Total (kg)', 'type' => 'number', 'step' => '0.01'],
  ['name' => 'reciclavel', 'label' => 'Peso Reciclável (kg)', 'type' => 'number', 'step' => '0.01']
];
