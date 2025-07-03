<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['customer_id'])) {
  echo "<script>
    alert('You must be logged in to view your cart.');
    window.location.href = 'login.php';
  </script>";
  exit;
}

$customer_id = $_SESSION['customer_id'];
$cust_result = mysqli_query($DBConnect, "SELECT customer_name, email, contact_number, address, shipping_address FROM customer WHERE customer_id = $customer_id");
$cust = mysqli_fetch_assoc($cust_result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payment_method = mysqli_real_escape_string($DBConnect, $_POST['payment_method']);
  $address = mysqli_real_escape_string($DBConnect, $_POST['address']);
  $date = date('Y-m-d');
  $total = 0;

  mysqli_query($DBConnect, "UPDATE customer SET shipping_address = '$address' WHERE customer_id = $customer_id");

  $cart = mysqli_query($DBConnect, "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.customer_id = $customer_id");
  if (mysqli_num_rows($cart) === 0) {
    die("Your cart is empty. <a href='offer.php'>Shop now!</a>");
  }

  while ($item = mysqli_fetch_assoc($cart)) {
    $total += $item['price'] * $item['quantity'];
  }

  mysqli_query($DBConnect,
    "INSERT INTO payment (customer_id, date, payment_method, total)
     VALUES ($customer_id, '$date', '$payment_method', $total)");
  $payment_id = mysqli_insert_id($DBConnect);

  mysqli_data_seek($cart, 0); 
  while ($item = mysqli_fetch_assoc($cart)) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    mysqli_query($DBConnect,
      "INSERT INTO order_item (product_id, quantity, price)
       VALUES ($product_id, $quantity, $price)");
  }

  $_SESSION['shipping_address'] = $address; 

  header("Location: shipping.php");
  exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - LleMar School Stationaries</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; padding-bottom: 20px; }
    .form-container { background-color: #F0ECEB; padding: 2rem; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 700px; display: flex; flex-direction: column; }
    .btn-primary { background-color: #630A28; border-color: #630A28; }
    .btn-primary:hover { background-color: #4a071e; border-color: #4a071e; }
  </style>
</head>
<body>
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
                  <a class="nav-link active border-bottom border-danger border-3" href="cart.php">
                    <i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
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

<h1 class="text-center mt-5">Checkout</h1>

<form method="POST">
  <div class="row gap-4 mx-auto" style="max-width: 1200px;">
    <div class="col">
      <div class="form-container">
        <h2>Your Information</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($cust['customer_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($cust['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($cust['contact_number']) ?></p>

        <div class="mb-3">
          <label for="address" class="form-label">Delivery Address</label>
          <textarea class="form-control" name="address" id="address" required><?= htmlspecialchars($cust['address']) ?></textarea>
        </div>

        <div class="mb-3">
          <label for="payment_method" class="form-label">Payment Method</label>
          <select class="form-select" name="payment_method" id="payment_method" required>
            <option value="cash">Cash</option>
            <option value="gcash">G Cash</option>
            <option value="credit_card">Credit Card</option>
          </select>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="form-container">
        <h5>Cart Summary</h5>
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cart = mysqli_query($DBConnect, "SELECT c.*, p.product_name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.customer_id = $customer_id");
            $grand_total = 0;
            while ($item = mysqli_fetch_assoc($cart)) {
              $subtotal = $item['price'] * $item['quantity'];
              $grand_total += $subtotal;
              echo "<tr>
                      <td>".htmlspecialchars($item['product_name'])."</td>
                      <td>₱".number_format($item['price'], 2)."</td>
                      <td>".$item['quantity']."</td>
                      <td>₱".number_format($subtotal, 2)."</td>
                    </tr>";
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Grand Total</th>
              <th>₱<?= number_format($grand_total, 2) ?></th>
            </tr>
          </tfoot>
        </table>
        <button type="submit" class="btn btn-primary w-100">Confirm Checkout</button>
      </div>
    </div>
  </div>
</form>

<footer class="text-center">
  <div class="container">
    <p class="text-dark small">© 2025 LleMar School Stationaries. All rights reserved.</p>
    <p class="text-dark small">Llego, Andrew Nicole D. | Marciano, Latisha D.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
