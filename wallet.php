<?php
if (!function_exists('createWallet')) {
function createWallet($name) {
$wallets = json_decode(file_get_contents('wallets.json'), true);

$id = uniqid();
$wallets[$id] = [
'name' => $name,
'balance' => 1000, // Initial balance
];

file_put_contents('wallets.json', json_encode($wallets, JSON_PRETTY_PRINT));
return $id;
}
}

if (!function_exists('getWallet')) {
function getWallet($id) {
$wallets = json_decode(file_get_contents('wallets.json'), true);
return $wallets[$id] ?? null;
}
}
