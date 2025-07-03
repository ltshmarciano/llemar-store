<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['customer_id'])) {
  echo "<script>
    alert('You must be logged in to view our offers.');
    window.location.href = 'login.php';
  </script>";
  exit;
}

$customer_id = $_SESSION['customer_id'];
$message = "";

$cartQuantity = 0;
$sqlQty = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE customer_id = $customer_id";
$resultQty = mysqli_query($DBConnect, $sqlQty);
if ($rowQty = mysqli_fetch_assoc($resultQty)) {
    $cartQuantity = (int)$rowQty['total_quantity'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['qty'], $_POST['price'])) {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);
    $price = floatval($_POST['price']);
    if ($qty < 1) $qty = 1;

    $check = mysqli_prepare($DBConnect, "SELECT quantity FROM cart WHERE customer_id = ? AND product_id = ?");
    mysqli_stmt_bind_param($check, "ii", $customer_id, $product_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $update = mysqli_prepare($DBConnect, "UPDATE cart SET quantity = quantity + ? WHERE customer_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($update, "iii", $qty, $customer_id, $product_id);
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
        $message = "Cart updated!";
    } else {
        $insert = mysqli_prepare($DBConnect, "INSERT INTO cart (customer_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "iiid", $customer_id, $product_id, $qty, $price);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
        $message = "Added to cart!";
    }

    mysqli_stmt_close($check);
}

// Build sort
$sortSql = "";
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'cheapest') {
        $sortSql = "ORDER BY price ASC";
    } elseif ($_GET['sort'] == 'newest') {
        $sortSql = "ORDER BY product_id DESC";  
    } elseif ($_GET['sort'] == 'ratings') {
        $sortSql = "ORDER BY ratings DESC";  
    }
}

$query = "SELECT * FROM products $sortSql";
$result = mysqli_query($DBConnect, $query);
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
    body { font-family: 'Poppins', sans-serif; }
    .product-card {
        background-color:rgb(143, 142, 142);
        border: 1px solid #ddd;
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: white;
        text-decoration: none;
        width: 280px;
    }
    .product-card:hover { transform: scale(1.03); }
    .image-wrapper {
        aspect-ratio: 1 / 1;
        width: 100%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .product-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
    }
    .quantity-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .quantity-group input {
        max-width: 60px;
    }
    .star {
        color: gold;
        font-size: 1.2rem;
    }
    .recommendation .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 15px;
        margin-top: 5px;
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
                <li class="nav-item"><a class="nav-link active border-bottom border-danger border-3" href="offer.php"><i class="fa-solid fa-shop"></i> What We Offer</a></li>
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

<section class="container text-center py-5 my-5">
    <h1 class="hero-title mb-4">Check out our <span style="color: #630A28;">Offers</span></h1>

    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <form method="GET" class="d-flex align-items-center gap-2">
            <select class="form-select w-auto" id="sortSelect" name="sort" onchange="this.form.submit()">
                <option value="">Sort by</option>
                <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Newest</option>
                <option value="cheapest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'cheapest') ? 'selected' : '' ?>>Cheapest</option>
                <option value="ratings" <?= (isset($_GET['sort']) && $_GET['sort'] == 'ratings') ? 'selected' : '' ?>>Ratings</option>
            </select>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="container bg-light rounded-3 p-4" style="box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col d-flex justify-content-center">
                    <div class="product-card">
                        <div class="image-wrapper">
                            <img src="assets/<?= htmlspecialchars($row['image_url']) ?>" class="product-image" alt="<?= htmlspecialchars($row['product_name']) ?>">
                        </div>
                        <div class="product-info">
                            <h5 class="product-name"><?= htmlspecialchars($row['product_name']) ?></h5>
                            <p class="price">₱<?= number_format($row['price'], 2) ?></p>

                            <!-- Star Rating -->
                            <div class="rating">
                                <?php
                                $rating = $row['ratings'] ?? 0;
                                $fullStars = floor($rating / 2);
                                $halfStars = ($rating % 2) > 0 ? 1 : 0;
                                $emptyStars = 5 - $fullStars - $halfStars;
                                ?>
                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                    <span class="star">&#9733;</span> <!-- Full Star -->
                                <?php endfor; ?>
                                <?php for ($i = 0; $i < $halfStars; $i++): ?>
                                    <span class="star">&#9734;</span> <!-- Half Star -->
                                <?php endfor; ?>
                                <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                    <span class="star">&#9734;</span> <!-- Empty Star -->
                                <?php endfor; ?>
                            </div>

                            <!-- Recommendation -->
                            <p class="recommendation text-muted">
                                <?php 
                                if ($row['recommended'] == 'Highly Recommended') {
                                    echo '<span class="badge bg-success">Highly Recommended</span>';
                                }
                                ?>
                            </p>

                            <form method="post">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <input type="hidden" name="price" value="<?= $row['price'] ?>">
                                <div class="quantity-group">
                                    <label for="qty">Qty:</label>
                                    <input type="number" name="qty" value="1" min="1" class="form-control">
                                    <button type="submit" class="btn" style="background-color: #7D2B32; color: white;">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<footer class="text-center">
  <div class="container">
    <p class="text-dark small">© 2025 LleMar School Stationaries. All rights reserved.</p>
    <p class="text-dark small">Llego, Andrew Nicole D. | Marciano, Latisha D.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
