<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$users = json_decode(file_get_contents('users.json'), true) ?? [];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

if (isset($users[$email])) {
$msg = "⚠️ Email already registered.";
} else {
$users[$email] = ['password' => $password];
file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
$msg = "✅ Registered successfully. <a href='login.php' style='color:#4efcbf;'>Login here</a>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
 <head>
<meta charset="UTF-8">
<title>LuciChain Register</title>
<link rel="stylesheet" href="style.css">
<style>
body {
margin: 0;
background: linear-gradient(to right top, #1f1c2c, #928dab);
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
color: #fff;
}

.register-box {
background: rgba(255, 255, 255, 0.06);
backdrop-filter: blur(16px);
border: 1px solid rgba(255, 255, 255, 0.15);
border-radius: 20px;
padding: 2.5rem;
width: 90%;
max-width: 420px;
text-align: center;
box-shadow: 0 0 40px rgba(106, 17, 203, 0.3);
animation: fadeIn 0.8s ease;
}

h2 {
margin-bottom: 1.5rem;
font-size: 2rem;
color: #f0f0f0;
}

input {
width: 100%;
padding: 0.8rem;
margin: 0.6rem 0;
border: none;
border-radius: 12px;
font-size: 1rem;
outline: none;
background: rgba(255, 255, 255, 0.1);
color: white;
}

input::placeholder {
color: #bbb;
}

button {
width: 100%;
padding: 0.8rem;
margin-top: 1rem;
background: linear-gradient(135deg, #9333ea, #6a11cb);
color: white;
border: none;
border-radius: 12px;
font-weight: bold;
font-size: 1rem;
cursor: pointer;
transition: 0.3s ease;
box-shadow: 0 0 12px rgba(106, 17, 203, 0.4);
}

button:hover {
background: linear-gradient(135deg, #6a11cb, #9333ea);
transform: scale(1.04);
}

.msg {
margin-top: 1rem;
font-size: 0.95rem;
color: #ffd2d2;
}

a {
color: #c4f2ff;
text-decoration: none;
}

a:hover {
text-decoration: underline;
}

@keyframes fadeIn {
from { opacity: 0; transform: translateY(10px); }
to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="register-box">
 <form method="post">
 <h2>📝 Register</h2>
 <input name="email" placeholder="Email" required>
 <input name="password" placeholder="Password" type="password" required>
 <button>Register</button>
 <p class="msg"><?= $msg ?? "" ?></p>
 </form>
</div>

</body>
</html>
