<?php
session_start();
include("db_connect.php");
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = mysqli_prepare($DBConnect, "SELECT customer_id FROM customer WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $customer_id);
        mysqli_stmt_fetch($stmt);
        $_SESSION["customer_id"] = $customer_id;
        header("Location: account.php");
        exit;
    } else {
        $loginError = '<div class="alert alert-danger" role="alert">Invalid username or password.</div>';
    }

    mysqli_stmt_close($stmt);
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
      text-align: center;
      padding: 4rem 2rem 0rem;
    }

    .hero-section h1 {
      font-size: 2.5rem;
      font-weight: 700;
    }

    .hero-section p {
      color: #060606;
      font-size: 1.1rem;
      max-width: 600px;
      margin: 0 auto;
    }

    .form-container {
      background-color: #F0ECEB;
      color: black;
      border-radius: 12px;
      padding: 2rem;
      width: 100%;
      max-width: 500px;
    }

    .fullscreen-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 80vh;
      padding: 2rem;
    }

    .mint-shadow {
      box-shadow: 0 0 10px 4px rgba(129, 116, 116, 0.46);
    }

    .login:hover {
        background-color: rgb(66, 5, 25) !important;
        border-color: rgb(66, 5, 25) !important;
        color: white !important;
    }

    .register:hover {
        color: white !important;
        background-color: #630A28 !important;
    }

    .register:hover .text-hover-span {
        color: white !important;
    }

  </style>
</head>
<body class="mx-auto">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background: #DCD8CC;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="font-family: Georgia; font-weight: bold; color: #630A28;">
            <i class="fa-solid fa-palette fa-xl" style="color: #630A28;"></i>  LLEMAR STATIONARIES
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="offer.php"><i class="fa-solid fa-shop"></i> What We Offer</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                   <li class="nav-item"> <?php if (isset($_SESSION['customer_id'])): ?>
                    <a class="nav-link" href="account.php"><i class="fa-solid fa-user"></i> Account</a>
                <?php else: ?>
                    <a class="nav-link active border-bottom border-danger border-3" href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                <?php endif; ?></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- HERO SECTION -->
        <div class="hero-section">
            <h1>Welcome Back to <span style="color: #630A28;">Llemar Stationaries</span></h1>
            <p>One-Stop Shop for School Supplies! We're so glad you're here! Whether you're a student, parent, or teacher, we have everything you need to make the school year a success.</p>
        </div>

        <!-- LOGIN FORM -->
        <div class="fullscreen-wrapper">
            <div class="form-container mint-shadow">
                <h3 class="text-center mb-4 fw-bold"><i class="fa-solid fa-right-to-bracket" style="color: #0c0d21;"></i> Login</h3>
                <hr>

                <?php if (!empty($loginError)) echo $loginError; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="text-center mt-4 d-grid gap-2">
                        <button type="submit" class="btn btn-primary login" style="background: #630A28; border: #630A28;">Login</button>
                        <a href="register.php" class="btn btn-outline-secondary register">New User? <span class="text-primary text-hover-span">Create Account</span></a>
                    </div>
                </form>
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
