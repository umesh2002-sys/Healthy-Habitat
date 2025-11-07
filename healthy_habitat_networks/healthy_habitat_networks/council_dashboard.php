<?php
// session_start(); // If you need to ensure the council is logged in, uncomment this line

require 'config.php';  // Ensure the connection to the database is established

$message = '';

// Handle adding a location
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_location'])) {
    $location_name = $_POST['location_name']; // Only location name

    // Check if the location already exists
    $sql = "SELECT * FROM locations WHERE location_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$location_name]);

    if ($stmt->rowCount() > 0) {
        $message = "<div class='alert alert-warning'>Location already exists!</div>";
    } else {
        // Insert location into the locations table
        $sql = "INSERT INTO locations (location_name) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$location_name]);
            $message = "<div class='alert alert-success'>Location added successfully!</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Handle deleting a location
if (isset($_GET['delete'])) {
    $location_id = $_GET['delete'];

    $sql = "DELETE FROM locations WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$location_id]);
    header("Location: council_dashboard.php"); // Redirect after deletion
    exit();
}

// Handle editing a location
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_location'])) {
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];

    // Update location name
    $sql = "UPDATE locations SET location_name = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$location_name, $location_id]);
    $message = "<div class='alert alert-success'>Location updated successfully!</div>";
}

// Fetch locations for display
$areas = $pdo->query("SELECT * FROM locations")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Council Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
    <style>
        .footer {
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
<body class="businessbg">
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
                    <a href="adminindex.php" class="btn btn-primary ms-3">Back</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container" style="padding-top: 100px;">
    <h2 class="text-center mb-4">Council Dashboard</h2>

    <?= $message ?>

    <!-- Add Location Form -->
    <form method="POST">
    <div class="row mb-3 align-items-end">
    <div class="col-md-5">
        <label for="location_name" class="form-label">Location Name</label>
        <input type="text" name="location_name" class="form-control" id="location_name" required>
    </div>
    <div class="col-md-3">
        <label class="form-label d-none d-md-block">&nbsp;</label> <!-- empty label for spacing -->
        <button type="submit" name="add_location" class="btn btn-primary w-50">Add Location</button>
    </div>
</div>

    </form>

    <!-- Display Locations as Cards -->
    <h3 class="mt-4">Locations</h3>
    <div class="row">
        <?php foreach ($areas as $area): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($area['location_name']); ?></h5>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="location_id" value="<?= $area['id']; ?>">
                            <input type="text" name="location_name" class="form-control" value="<?= $area['location_name']; ?>" required>
                            <button type="submit" name="update_location" class="btn btn-warning mt-2">Update</button>
                        </form>
                        <a href="?delete=<?= $area['id'] ?>" class="btn btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this location?');">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Footer -->
<div class="footer">
    <p style="color:white">&copy; 2025 Healthy Habitat. All Rights Reserved.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
