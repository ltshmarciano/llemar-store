<?php
session_start();
include("db_connect.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['fullname']);
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $phoneNumber = trim($_POST['phoneNumber']);
  $email = trim($_POST['inputEmail']);
  $street = trim($_POST['street']);
  $city = trim($_POST['city']);
  $province = trim($_POST['province']);
  $zipCode = trim($_POST['zipCode']);
  $country = trim($_POST['country']);
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  $today = new DateTime();
  $dobObj = DateTime::createFromFormat('Y-m-d', $dob);

  if (!preg_match("/^[A-Za-z\s]{2,50}$/", $name)) {
    $errors[] = "Full name must be 2–50 characters long and contain only letters and spaces.";
  }
  if (!$dobObj || $dobObj->format('Y-m-d') !== $dob) {
    $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
  } elseif ($dobObj > $today) {
    $errors[] = "Date of birth cannot be in the future.";
  } else {
    $age = $today->diff($dobObj)->y;
    if ($age < 13) {
      $errors[] = "You must be at least 13 years old.";
    }
  }
  if (!preg_match("/^\+?\d{10,15}$/", $phoneNumber)) {
    $errors[] = "Phone number must be 10 to 15 digits, optionally starting with +.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address format.";
  }
  if (!preg_match("/^[A-Za-z\s]{2,50}$/", $street)) {
    $errors[] = "Street must be 2–50 characters long and contain only letters and spaces.";
  }
  if (!preg_match("/^[A-Za-z\s]{2,50}$/", $city)) {
    $errors[] = "City must be 2–50 characters long and contain only letters and spaces.";
  }
  if (!preg_match("/^[A-Za-z\s]{2,50}$/", $province)) {
    $errors[] = "Province must be 2–50 characters long and contain only letters and spaces.";
  }
  if (!preg_match("/^\d{4}$/", $zipCode)) {
    $errors[] = "ZIP code must be exactly 4 digits.";
  }
  if (!preg_match("/^[A-Za-z\s]{2,50}$/", $country)) {
    $errors[] = "Country must be 2–50 characters long and contain only letters and spaces.";
  }
  if (!preg_match("/^[A-Za-z0-9_]{4,20}$/", $username)) {
    $errors[] = "Username must be 4–20 characters long and contain only letters, numbers, or underscores.";
  }
  if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters long.";
  } elseif ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
  }

  if (empty($errors)) {
    $stmt = mysqli_prepare($DBConnect, "SELECT customer_id FROM CUSTOMER WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
      $errors[] = "Username or email already exists.";
    }
    mysqli_stmt_close($stmt);
  }

  if (empty($errors)) {
    $fullAddress = "$street, $city, $province $zipCode, $country";

    $stmt = mysqli_prepare($DBConnect, "INSERT INTO CUSTOMER 
      (customer_name, address, contact_number, email, username, password) 
      VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $fullAddress, $phoneNumber, $email, $username, $password);

    if (mysqli_stmt_execute($stmt)) {
      echo "<script>
        alert('Registration successful! Redirecting to login...');
        window.location.href = 'login.php';
      </script>";
      exit;
    } else {
      $errors[] = "Database error: " . htmlspecialchars(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - LleMar Stationaries</title>
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
        background-color:rgb(66, 5, 25) !important;
        border-color: rgb(66, 5, 25) !important;
        color: white !important;
    }
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

<div class="fullscreen-wrapper">
  <div class="form-container">
    <h2 class="text-center mb-4">Registration Form</h2>
    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <form method="post">
      <!-- Form fields (same as before) -->
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="fullname" name="fullname" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Gender</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" value="male" checked required>
          <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" value="female" required>
          <label class="form-check-label">Female</label>
        </div>
      </div>
      <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" required>
      </div>
      <div class="mb-3">
        <label for="phoneNumber" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
      </div>
      <div class="mb-3">
        <label for="inputEmail" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="inputEmail" name="inputEmail" required>
      </div>
      <div class="mb-3">
        <label for="street" class="form-label">Street</label>
        <input type="text" class="form-control" id="street" name="street" required>
      </div>
      <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" class="form-control" id="city" name="city" required>
      </div>
      <div class="mb-3">
        <label for="province" class="form-label">Province</label>
        <input type="text" class="form-control" id="province" name="province" required>
      </div>
      <div class="mb-3">
        <label for="zipCode" class="form-label">Zip Code</label>
        <input type="text" class="form-control" id="zipCode" name="zipCode" required>
      </div>
      <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" class="form-control" id="country" name="country" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn login" style="background: #630A28; border: #630A28; color:#ffffff;">Register</button>
      </div>
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
