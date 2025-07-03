<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit;
}

$customer_id = $_SESSION['customer_id'];
$result = mysqli_query($DBConnect, "SELECT customer_name, address, contact_number, username, email FROM customer WHERE customer_id = $customer_id");
$customer = mysqli_fetch_assoc($result);

// Get cart quantity
$cartQuantity = 0;
$sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE customer_id = $customer_id";
$res = mysqli_query($DBConnect, $sql);
if ($row = mysqli_fetch_assoc($res)) {
    $cartQuantity = (int)$row['total_quantity'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LleMar School Stationaries</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .hero-section {
        background-color: #DCD8CC;
        padding: 0;
    }
  </style>
</head>
<body class="mx-auto">

<nav class="navbar navbar-expand-lg" style="background: #DCD8CC;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#" style="font-family: Georgia; font-weight: bold; color: #630A28;">
            <i class="fa-solid fa-palette fa-xl" style="color: #630A28;"></i> LLEMAR STATIONARIES
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="offer.php"><i class="fa-solid fa-shop"></i> What We Offer</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fa-solid fa-cart-shopping"></i> Cart
                        <?php if ($cartQuantity > 0): ?>
                          <span class="badge bg-danger"><?= $cartQuantity ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active border-bottom border-danger border-3" href="account.php"><i class="fa-solid fa-user"></i> Account</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
  <div class="profile-section mx-auto" style="max-width: 700px;">
    <h2 class="text-center mb-4">Your Account Details</h2>
    <p><strong>Full Name:</strong> <?= htmlspecialchars($customer['customer_name']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($customer['address']) ?></p>
    <p><strong>Contact Number:</strong> <?= htmlspecialchars($customer['contact_number']) ?></p>
    <p><strong>Username:</strong> <?= htmlspecialchars($customer['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
    <div class="text-center mt-4">
      <a href="logout.php" class="btn px-4 py-2 rounded-pill">
        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
      </a>
    </div>
  </div>
</div>

<footer class="text-center">
  <div class="container">
    <p class="text-dark small">© 2025 LleMar School Stationaries. All rights reserved.</p>
    <p class="text-dark small">Llego, Andrew Nicole D. | Marciano, Latisha D.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
