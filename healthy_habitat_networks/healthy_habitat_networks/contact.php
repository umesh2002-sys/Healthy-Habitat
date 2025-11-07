<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Healthy Habitat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #f1f8e9 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .contact-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 60px;
        }
        .contact-header {
            font-weight: bold;
            font-size: 2rem;
            color: #2e7d32;
        }
        .contact-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .footer {
            color: white;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #2e7d32;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body style="background: url('images/contact.png') no-repeat center center fixed; background-size: cover;">


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #2e7d32;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/icon.webp" alt="Healthy Habitat Logo" style="width: 50px;">
        </a>
        <div class="collapse navbar-collapse justify-content-end" style="font-weight: bold;">
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <!-- Back Button -->
                    <button class="btn btn-secondary ms-3" onclick="history.back();">Back</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contact Section -->
<div class="container contact-container" style="    background: center;">
    <div class="row">
        <div class="col-md-6">
            <div class="contact-header mb-4">Get in Touch</div>
            <form method="POST" action="#">
                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Your Email</label>
                    <input type="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" rows="5" placeholder="Your message" required></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Send Message</button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="contact-info">
                <h5>Contact Information</h5>
                <p><strong>Email:</strong> info@healthyhabitat.com</p>
                <p><strong>Phone:</strong> +1 234 567 890</p>
                <p><strong>Address:</strong> 123 Green Street, Eco City, Planet Earth</p>
                <p><strong>Working Hours:</strong> Mon - Fri: 9AM - 6PM</p>
            </div>
        </div>
    </div>
    <!-- Success Alert (hidden by default) -->
<div id="successAlert" class="alert alert-success mt-3 d-none" role="alert">
    Message sent successfully!
</div>

</div>

<!-- Footer -->
<div class="footer">
    &copy; 2025 Healthy Habitat. All Rights Reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent actual form submission

    // Show the success alert
    document.getElementById('successAlert').classList.remove('d-none');

    // Optionally, reset the form
    this.reset();

    // Hide the alert after 3 seconds
    setTimeout(() => {
        document.getElementById('successAlert').classList.add('d-none');
    }, 3000);
});
</script>

</body>
</html>
