<?php
session_start();
if (!isset($_SESSION['user'])) {
header("Location: login.php");
exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Welcome to LuciChain</title>
<link rel="stylesheet" href="style.css">
<style>
body {
background: radial-gradient(circle at top left, #9333ea, #0d1117);
min-height: 100vh;
display: flex;
justify-content: center;
align-items: center;
color: #fff;
animation: fadeIn 1s ease;
}

@keyframes fadeIn {
from { opacity: 0; transform: translateY(10px); }
to { opacity: 1; transform: translateY(0); }
}

.glass-card {
background: rgba(255, 255, 255, 0.1);
border: 1px solid rgba(255, 255, 255, 0.1);
backdrop-filter: blur(15px);
border-radius: 20px;
padding: 3rem;
text-align: center;
box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
width: 90%;
max-width: 500px;
}

.glass-card h2 {
font-size: 2rem;
margin-bottom: 1rem;
}

.glass-card p {
margin-bottom: 2rem;
font-size: 1.1rem;
color: #ccc;
}

.btn {
padding: 0.8rem 1.6rem;
border: none;
border-radius: 12px;
background: #9333ea;
color: white;
font-weight: bold;
font-size: 1rem;
cursor: pointer;
transition: 0.3s;
margin: 0.5rem;
text-decoration: none;
display: inline-block;
}

.btn:hover {
background: #6a00ff;
transform: scale(1.05);
}

.logout {
background: #ff3b3b;
}

.logout:hover {
background: #cc0000;
}

footer {
position: absolute;
bottom: 10px;
width: 100%;
text-align: center;
font-size: 0.9rem;
color: #888;
}
</style>
</head>
<body>

<div class="glass-card">
<h2>👋 Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
<p>You’ve successfully logged into <strong>LuciChain</strong> — your decentralized wallet.</p>

<a href="index.php" class="btn">🚀 Open LuciChain</a>
<a href="logout.php" class="btn logout">🚪 Logout</a>
</div>

<footer>© 2025 LuciChain | Built with ❤️ by Raja Sharma</footer>

</body>
</html>
