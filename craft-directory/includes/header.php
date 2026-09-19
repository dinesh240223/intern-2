<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Traditional Craft Directory — Discover and connect with verified Indian artisans directly.">
<title>Traditional Craft Directory — India's Living Artisan Heritage</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;0,9..144,900;1,9..144,600;1,9..144,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a href="index.php" class="logo">
      <span class="logo-mark">◆</span> Craft<span>Directory</span>
    </a>
    <nav class="main-nav" id="mainNav">
      <a href="index.php">Home</a>
      <a href="crafts.php">Crafts</a>
      <a href="artisans.php">Artisans</a>
      <a href="about.php">About</a>
      <a href="contact.php">Contact</a>
      <a href="admin/login.php" class="btn-admin">Admin Login</a>
    </nav>
    <button class="menu-toggle" aria-label="Menu">☰</button>
  </div>
</header>
<main>