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
    /* Team Cards */
    .team-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background-color: #f8f9fa;
        transition: transform 0.2s ease-in-out;
    }
    .team-card:hover {
        transform: scale(1.05);
    }
    .team-card img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        margin-bottom: 15px;
    }
    .team-card h5 {
        font-weight: bold;
        color: #630A28;
    }
    .team-card p {
        color: #777;
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
                <li class="nav-item"><a class="nav-link active border-bottom border-danger border-3" href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
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

<!-- ABOUT US SECTION -->
<section class="container py-5 my-5">
    <div class="registration-container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="text-center mb-4">About <span class="fw-bold" style="color: #630A28;">LleMar School Stationaries</span></h1>
                <h3>Company Information</h3>
                <p class="mb-4 text-dark px-3" style="text-align: justify;">
                LleMar School Stationaries is a student-led project designed by second-year college students from the University of the East Manila. 
                Our mission is to provide an efficient, affordable, and convenient online platform for students to purchase all their school supplies.
                With our website, we aim to reduce the hassle of finding quality school materials, ensuring that every student has easy access to the resources they need for their academic success.
                </p>
            </div>

            <div class="col-md-6">
                <h3>Contact Us</h3>
                <p class="mb-4 text-dark">
                If you have any questions, concerns, or need help with your orders, feel free to contact us. We're here to help!
                </p>

                <form action="contact_form.php" method="post">
                    <div class="mb-3 text-dark">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3 text-dark">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3 text-dark">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn w-100 text-white" style="background-color: #630A28;">Send Message</button>
                </form>

                <h3 class="mt-4 text-dark">Our Contact Information</h3>
                <ul>
                    <li class="text-dark"><strong>Email:</strong> support@llemarstationaries.com</li>
                    <li class="text-dark"><strong>Phone:</strong> +63 912 345 6789</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Mission and Vision Section -->
<section class="container py-5">
    <h2 class="text-center mb-5" style="color: #630A28;">Our Mission and Vision</h2>
    <div class="container bg-light py-5 rounded-3">
        <div class="row">
            <div class="col-md-6">
                <h3>Our Mission</h3>
                <p style="text-align: justify;">
                    Our mission is to provide an efficient, affordable, and convenient online platform for students to purchase all their school supplies. 
                    We aim to make academic success easier by ensuring that every student has access to the necessary tools for their learning.
                </p>
            </div>
            <div class="col-md-6">
                <h3>Our Vision</h3>
                <p style="text-align: justify;">
                    We envision a future where students can easily access all the resources they need for their academic success. Our goal is to become the go-to platform 
                    for students to buy quality school supplies at affordable prices, with convenience being at the heart of our service.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Meet Our Team Section -->
<section class="container py-5">
    <h2 class="text-center mb-5" style="color: #630A28;">Meet Our Team</h2>
    <div class="row justify-content-center">
        <!-- Team Member 1 -->
        <div class="col-md-4">
            <div class="team-card">
                <img src="assets\Marciano_photo.jpg" alt="Team Member 1">
                <h5>Latisha Marciano</h5>
                <p>Co-founder & CEO</p>
            </div>
        </div>
        <!-- Team Member 2 -->
        <div class="col-md-4">
            <div class="team-card">
                <img src="assets\Llego_photo.jpg" alt="Team Member 2">
                <h5>Andrew Nicole Llego</h5>
                <p>Co-founder & CTO</p>
            </div>
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
