<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['customer_id'])) {
    echo "<script>
        alert('You must be logged in to view this page.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$customer_id = $_SESSION['customer_id'];

if (isset($_POST['clear_cart'])) {
    mysqli_query($DBConnect, "DELETE FROM cart WHERE customer_id = $customer_id");

    echo "<script>
        window.location.href = 'offer.php'; // Redirect to the shop page
    </script>";
    exit; 
}

$cartQuantity = 0;
$sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE customer_id = $customer_id";
$result = mysqli_query($DBConnect, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    $cartQuantity = (int)$row['total_quantity'];
}

$cust_query = mysqli_query($DBConnect, "SELECT customer_name, email, contact_number FROM customer WHERE customer_id = $customer_id");
$cust = mysqli_fetch_assoc($cust_query);

$cart_query = mysqli_query($DBConnect, "SELECT c.*, p.product_name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.customer_id = $customer_id");
$cart_items = [];
$total_cost = 0;
while ($item = mysqli_fetch_assoc($cart_query)) {
    $cart_items[] = $item;
    $total_cost += $item['price'] * $item['quantity'];  
}

$payment_query = mysqli_query($DBConnect, "SELECT payment_method FROM payment WHERE customer_id = $customer_id ORDER BY date DESC LIMIT 1");
$payment = mysqli_fetch_assoc($payment_query);

$payment_method = isset($payment['payment_method']) ? $payment['payment_method'] : 'Not Specified';

$address = isset($_SESSION['shipping_address']) ? $_SESSION['shipping_address'] : 'Not Provided';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shipping - LleMar School Stationaries</title>
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

<div class="container my-5">
    <div class="alert alert-success">
        <h4 class="alert-heading">Checkout Complete!</h4>
        <p>Thank you for your purchase. Your order will be delivered to:</p>
        <p><strong><?= htmlspecialchars($address) ?></strong></p>
        <hr>

        <h3>Order Receipt</h3>
        <div class="mb-3">
            <p><strong>Customer Name:</strong> <?= htmlspecialchars($cust['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($cust['email']) ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($cust['contact_number']) ?></p>
            <p><strong>Shipping Address:</strong> <?= htmlspecialchars($address) ?></p>
        </div>

        <h4>Products Purchased:</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total Cost: ₱<?= number_format($total_cost, 2) ?></h4>
        <h4>Payment Method: <?= htmlspecialchars(ucfirst($payment_method)) ?></h4>

        <hr>
        <form method="POST">
            <button class="btn btn-success" type ="submit" name = "clear_cart">Shop Again</button>
        </form>
        
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
