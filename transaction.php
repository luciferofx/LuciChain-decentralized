<?php
require 'wallet.php';
require_once 'blockchain.php';

function makeTransaction($from, $to, $amount) {
$wallets = json_decode(file_get_contents('wallets.json'), true);

if (!isset($wallets[$from]) || !isset($wallets[$to])) {
return "Wallet not found!";
}

if ($wallets[$from]['balance'] < $amount) {
return "Insufficient balance!";
}

$wallets[$from]['balance'] -= $amount;
$wallets[$to]['balance'] += $amount;

file_put_contents('wallets.json', json_encode($wallets, JSON_PRETTY_PRINT));

$tx = [
'from' => $from,
'to' => $to,
'amount' => $amount,
'timestamp' => time()
];

return addToBlockchain($tx);
}
