<?php
session_start();
require_once 'wallet.php';
require_once 'transaction.php';
require_once 'blockchain.php';
require_once 'qrlib.php';

$message = "";
$wallets = json_decode(file_get_contents('wallets.json'), true) ?? [];
$user_wallets = [];


// Create blockchain
$blockchain = new Blockchain();

// Only show wallets that belong to the logged-in user
if (isset($_SESSION['user'])) {
    foreach ($wallets as $id => $wallet) {
        if (isset($wallet['owner']) && $wallet['owner'] === $_SESSION['user']) {
            $user_wallets[$id] = $wallet;
        }
    }
}

// Handle wallet creation
if (isset($_POST['create_wallet']) && isset($_SESSION['user'])) {
    $name = trim($_POST['name']);
    if ($name !== "") {
        $id = uniqid();
        $wallets[$id] = [
            'name' => $name,
            'balance' => 1000,
            'owner' => $_SESSION['user']
        ];
        file_put_contents('wallets.json', json_encode($wallets, JSON_PRETTY_PRINT));
        header("Location: index.php?msg=created&id=$id");
        exit();
    } else {
        $message = "<p class='error'>⚠️ Wallet name cannot be empty.</p>";
    }
}
// Handle transfer
// Handle transfer
if (isset($_POST['transfer']) && isset($_SESSION['user'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $amount = (int) $_POST['amount'];

    if (!isset($wallets[$from]) || !isset($wallets[$to])) {
        $message = "<p class='error'>⚠️ Invalid wallet selected.</p>";
    } elseif ($wallets[$from]['owner'] !== $_SESSION['user']) {
        $message = "<p class='error'>⚠️ You can only send from your own wallet!</p>";
    } elseif ($wallets[$from]['balance'] < $amount) {
        $message = "<p class='error'>❌ Insufficient balance!</p>";
    } else {
        $wallets[$from]['balance'] -= $amount;
        $wallets[$to]['balance'] += $amount;
        file_put_contents('wallets.json', json_encode($wallets, JSON_PRETTY_PRINT));

        // Add to blockchain
        $blockchain = new Blockchain();
        $blockchain->loadChain();
        $txData = [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'timestamp' => time(),
            'by' => $_SESSION['user']
        ];
        $block = $blockchain->addBlock($txData);

        // Save hash
        $hashes = json_decode(file_get_contents('hashes.json'), true) ?? [];
        $hashes[] = [
            
           
            'from' => $from,
            'to' => $to,
            'amount' => $amount
        ];
        file_put_contents('hashes.json', json_encode($hashes, JSON_PRETTY_PRINT));

        $message = "<p class='success'>💸 ₹$amount transferred! Hash recorded on LuciChain.</p>";
    }
}

// Handle messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $message = "<p class='success'>✅ Wallet created successfully! ID: {$_GET['id']}</p>";
    } elseif ($_GET['msg'] === 'transferred') {
        $message = "<p class='success'>💸 ₹{$_GET['amt']} transferred successfully and recorded on LuciChain!</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LuciChain Wallet</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #2c2c54, #3c40c6);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #2f2f70;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      width: 300px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      background-color: white;
      color: black;
      font-size: 16px;
      margin-bottom: 20px;
      cursor: pointer;
    }

    select option {
      background-color: white;
      color: black;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      background: #8e2de2;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #4a00e0;
    }

    .rocket {
      margin-right: 8px;
    }
         body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        nav {
            background-color: #1f1f1f;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #333;
            min-width: 150px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.5);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #555;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            padding: 2rem;
        }

        .wallet {
            background-color: #1f1f1f;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        input, button {
            padding: 0.5rem;
            font-size: 1rem;
            margin: 0.5rem 0;
            background-color: #2c2c2c;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #3d3d3d;
            cursor: pointer;
        }

        form {
            margin-bottom: 2rem;
        }

        img {
            margin-top: 10px;
            border: 2px solid #444;
            border-radius: 5px;
        }

        /* Inline style from your previous code. Use separate style.css for better structure. */
        body {
            margin: 0;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 850px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        form {
            margin-bottom: 2rem;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            margin-top: 10px;
            font-size: 1rem;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            outline: none;
        }

        select {
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            border: 1px solid #6a11cb;
            padding: 12px 40px 12px 16px;
            color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg fill='white' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 16px;
        }

        input::placeholder {
            color: #ccc;
        }

        button {
            background: #6a11cb;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #2575fc;
            transform: scale(1.02);
        }

        .success {
            color: #00ffb3;
            font-weight: bold;
        }

        .error {
            color: #ff6b6b;
            font-weight: bold;
        }

        a {
            color: #93f;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        ul {
            padding-left: 1.5rem;
        }

        li {
            margin-bottom: 1.5rem;
            list-style: none;
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 12px;
        }

        img {
            margin-top: 10px;
            border-radius: 6px;
            background: white;
            padding: 5px;
        }

        .section-title {
            margin-top: 2rem;
            font-size: 1.4rem;
            border-left: 5px solid #93f;
            padding-left: 10px;
        }.form-select {
  background-color: white;
  color: black;
  border: 1px solid #ccc;
  padding: 8px;
  border-radius: 6px;
}

.form-select option {
  background-color: white;
  color: black;
}

    </style>
</head>
<body>
<div class="container">
    <h1>🚀 LuciChain Wallet</h1>

    <?php if (isset($_SESSION['user'])): ?>
    <p>
        👋 Logged in as <b><?= $_SESSION['user'] ?></b> |
        <a href="history.php">📜 History</a> |
        <a href="logout.php">Logout</a>
    </p>
<?php else: ?>
    <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
<?php endif; ?>

    <?= $message ?>

  

    <div class="section-title">👛 Create Wallet</div>
    <?php if (isset($_SESSION['user'])): ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Wallet Name" required>
            <button type="submit" name="create_wallet">➕ Create Wallet</button>
        </form>
    <?php else: ?>
        <p class="error">⚠️ You must <a href="login.php">log in</a> to create a wallet.</p>
    <?php endif; ?>

    <div class="section-title">💸 Make a Transfer</div>
    <?php if (isset($_SESSION['user'])): ?>
        <form method="POST">
            <select name="from" required>
                <option value="">From Wallet</option>
                <?php foreach ($user_wallets as $id => $wallet): ?>
                    <option value="<?= $id ?>"><?= $wallet['name'] ?> (<?= $id ?>)</option>
                <?php endforeach; ?>
            </select>
            <select name="to" required>
                <option value="">To Wallet</option>
                <?php foreach ($wallets as $id => $wallet): ?>
                    <option value="<?= $id ?>"><?= $wallet['name'] ?> (<?= $id ?>)</option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="amount" placeholder="Amount" min="1" required>
            <button type="submit" name="transfer">🚀 Send Money</button>
        </form>
    <?php else: ?>
        <p class="error">⚠️ You must <a href="login.php">log in</a> to transfer funds.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="section-title">📊 Wallet Balances + QR Code</div>
        <ul>
            <?php foreach ($user_wallets as $id => $wallet): ?>
                <li>
                    <b><?= $wallet['name'] ?></b> (<?= $id ?>)<br>
                    💰 Balance: ₹<?= $wallet['balance'] ?><br>
                    <?php
                    $qrPath = "qr_$id.png";
                    if (!file_exists($qrPath)) {
                        QRcode::png($id, $qrPath);
                    }
                    ?>
                    <img src="<?= $qrPath ?>" width="100" alt="QR for <?= $id ?>">
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>
