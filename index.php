<?php
session_start();
include('db_connect.php');

$cartQuantity = 0;

if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
    $sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE customer_id = $customer_id";
    $result = mysqli_query($DBConnect, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $cartQuantity = (int)$row['total_quantity'];
    }
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
                <li class="nav-item"><a class="nav-link active border-bottom border-danger border-3" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
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
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <a class="nav-link" href="account.php"><i class="fa-solid fa-user"></i> Account</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="container hero-section my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="./assets/index-hero-section.jpg" class="img-fluid float-start" alt="Stationery Set">
        </div>
        <div class="col-md-6 align-items-center">
            <h1 class="display-4 text-center fw-bold" style="color: #630A28;">Stationery</h1>
            <p class="fs-5 text-muted text-center">
                An assortment of well-designed stationery items for daily use. Whether for work, study, or just jotting down notes, there is plenty for you to choose from.
            </p>
            <a href="offer.php" class="btn btn-danger btn-lg mt-3 d-block mx-auto" style="width: max-content;">Explore Our Products</a>
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
