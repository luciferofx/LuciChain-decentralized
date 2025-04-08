<?php
session_start();
$hashes = json_decode(file_get_contents('hashes.json'), true) ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🔗 Transaction History - LuciChain</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #111;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            padding: 2rem;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #1f1f1f;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 20px #6a11cb88;
        }
        h1 {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 1.2rem;
            background: #2e2e2e;
            padding: 1rem;
            border-radius: 8px;
        }
        code {
            color: #0ff;
            word-break: break-all;
        }
        a {
            color: #6a11cb;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔗 LuciChain Transaction History</h1>
    <p><a href="index.php">← Back to Dashboard</a></p>
    <ul>
        <?php foreach (array_reverse($hashes) as $tx): ?>
            <li>
                <b>💸 ₹<?= $tx['amount'] ?></b><br>
                🔄 <b>From:</b> <?= $tx['from'] ?> <b>To:</b> <?= $tx['to'] ?><br>
           
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
