<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['customer_id'])) {
  echo "<script>
    alert('You must be logged in to view your cart.');
    window.location.href = 'login.php';
  </script>";
  exit;
}

$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['cart_id'])) {
        $cart_id = (int)$_POST['cart_id'];
        $action = $_POST['action'];

        if ($action === 'increase') {
            mysqli_query($DBConnect, "UPDATE cart SET quantity = quantity + 1 WHERE cart_id = $cart_id AND customer_id = $customer_id");
        } elseif ($action === 'decrease') {
            $res = mysqli_query($DBConnect, "SELECT quantity FROM cart WHERE cart_id = $cart_id AND customer_id = $customer_id");
            if ($row = mysqli_fetch_assoc($res)) {
                if ($row['quantity'] > 1) {
                    mysqli_query($DBConnect, "UPDATE cart SET quantity = quantity - 1 WHERE cart_id = $cart_id AND customer_id = $customer_id");
                } else {
                    mysqli_query($DBConnect, "DELETE FROM cart WHERE cart_id = $cart_id AND customer_id = $customer_id");
                }
            }
        } elseif ($action === 'remove') {
            mysqli_query($DBConnect, "DELETE FROM cart WHERE cart_id = $cart_id AND customer_id = $customer_id");
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'clear') {
        mysqli_query($DBConnect, "DELETE FROM cart WHERE customer_id = $customer_id");
    }
}

// Fetch cart items
$cartItems = [];
$sql = "
SELECT cart.cart_id, cart.quantity, products.product_name, products.price
FROM cart
JOIN products ON cart.product_id = products.product_id
WHERE cart.customer_id = $customer_id
";
$result = mysqli_query($DBConnect, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $row['total'] = $row['price'] * $row['quantity'];
    $cartItems[] = $row;
}

// Compute cart quantity
$cartQuantity = 0;
foreach ($cartItems as $item) {
    $cartQuantity += $item['quantity'];
}

// Calculate total function
function calculateTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['total'];
    }
    return $total;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - LleMar School Stationaries</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .registration-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2.5rem;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,.1);
        border: 1px solid #e0e0e0;
    }
    h1 {
        background-color: #630A28 !important;
        color: #ecf0f1 !important;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        font-size: 2.2rem;
        font-weight: 600;
    }
    .btn-dark {
        background-color: #2c3e50;
        border-color: #2c3e50;
        padding: .8rem 1.8rem;
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 8px;
    }
    .btn-outline-dark {
        border-color: #2c3e50;
        padding: .8rem 1.8rem;
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 8px;
    }
    .btn-dark:hover, .btn-outline-dark:hover {
        background-color: #1abc9c;
        border-color: #1abc9c;
        transform: translateY(-2px);
    }
    footer {
        background-color: #FFFFFF;
        border-top: 2px solid rgba(0,255,183,0.2);
    }
    thead {
        background-color: #630A28;
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
                  <a class="nav-link active border-bottom border-danger border-3" href="cart.php">
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

<div class="fluid-container m-3">
  <div class="row justify-content-center">
    <div class="col-12 registration-container">
      <h1 class="text-center">Your Cart</h1>

      <?php if (empty($cartItems)): ?>
        <div class="alert alert-warning text-center text-dark">Your cart is currently empty.</div>
      <?php else: ?>
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cartItems as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td class="text-center">
                  <form method="post" class="d-inline">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="hidden" name="action" value="decrease">
                    <button class="btn btn-sm btn-outline-secondary" title="Decrease quantity">-</button>
                  </form>
                  <?= $item['quantity'] ?>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="hidden" name="action" value="increase">
                    <button class="btn btn-sm btn-outline-secondary" title="Increase quantity">+</button>
                  </form>
                </td>
                <td>₱<?= number_format($item['total'], 2) ?></td>
                <td>
                  <form method="post" onsubmit="return confirm('Remove this item from cart?');">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="hidden" name="action" value="remove">
                    <div class="d-grid gap-2">
                      <button class="btn btn-sm btn-danger md-block">Remove</button>
                    </div>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="text-end">
          <h4>Total: ₱<?= number_format(calculateTotal($cartItems), 2) ?></h4>
        </div>

        <div class="d-grid gap-2 mt-4">
          <a href="checkout.php" class="btn btn-dark w-100">Proceed to Checkout</a>
          <form method="post">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn btn-outline-dark w-100" onclick="return confirm('Clear your cart?');">Clear Cart</button>
          </form>
        </div>
      <?php endif; ?>
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
