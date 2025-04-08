<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$users = json_decode(file_get_contents('users.json'), true) ?? [];
$email = $_POST['email'];
$pass = $_POST['password'];

if (isset($users[$email]) && password_verify($pass, $users[$email]['password'])) {
$_SESSION['user'] = $email;
header("Location: dashboard.php");
exit;
} else {
$msg = "❌ Invalid login.";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LuciChain Login</title>
<link rel="stylesheet" href="style.css">
<style>
body {
margin: 0;
background: linear-gradient(to right top, #0f0c29, #302b63, #24243e);
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
color: #fff;
}

.login-box {
background: rgba(255, 255, 255, 0.05);
backdrop-filter: blur(20px);
border: 1px solid rgba(255, 255, 255, 0.1);
border-radius: 16px;
padding: 2.5rem;
width: 90%;
max-width: 400px;
text-align: center;
box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
animation: fadeIn 1s ease;
}

h2 {
margin-bottom: 1.5rem;
font-size: 2rem;
}

input {
width: 100%;
padding: 0.75rem;
margin: 0.5rem 0;
border: none;
border-radius: 10px;
font-size: 1rem;
outline: none;
background: rgba(255, 255, 255, 0.1);
color: white;
}

input::placeholder {
color: #aaa;
}

button {
width: 100%;
padding: 0.75rem;
margin-top: 1rem;
background-color: #9333ea;
color: white;
border: none;
border-radius: 10px;
font-weight: bold;
font-size: 1rem;
cursor: pointer;
transition: 0.3s;
}

button:hover {
background-color: #6a00ff;
transform: scale(1.03);
}

.error {
color: #ff4d4d;
margin-top: 1rem;
}

@keyframes fadeIn {
from { opacity: 0; transform: translateY(10px); }
to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="login-box">
<form method="post">
<h2>🔐 Login to LuciChain</h2>

<input name="email" type="email" placeholder="📧 Email" required>
<input name="password" type="password" placeholder="🔑 Password" required>
<button type="submit">🚀 Login</button>

<p class="error"><?= $msg ?? "" ?></p>
</form>
</div>

</body>
</html>
