<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="form-container">
        <h2>Register Here....</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection setup
            $host = "localhost";  
            $dbUsername = "root"; 
            $dbPassword = ""; 
            $dbName = "roc_task1"; 

            $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

            // Check connection
            if ($conn->connect_error) {
                die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
            }

            // Get form values
            $name = $_POST['name'] ?? '';
            $nic = $_POST['nic'] ?? '';
            $email = $_POST['email'] ?? '';
            $mobile = $_POST['mobile'] ?? '';

            // Validate fields
            if (empty($name) || empty($nic) || empty($email) || empty($mobile)) {
                echo "<p style='color:red;'>Please fill in all the fields.</p>";
            } else {
                // Check if email already exists
                $checkQuery = "SELECT * FROM register WHERE email = ?";
                $stmt = $conn->prepare($checkQuery);

                if (!$stmt) {
                    echo "<script>alert('Prepare Failed');</script>";
                } else {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<script>alert('Email already exist');</script>";
                    } else {
                        // Insert new record
                        $insertQuery = "INSERT INTO register (name, nic, email, mobile) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($insertQuery);

                        if (!$stmt) {
                            echo "<script>alert('Prepare Failed');</script>";
                        } else {
                            $stmt->bind_param("ssss", $name, $nic, $email, $mobile);

                            if ($stmt->execute()) {
                                echo "<script>alert('Registration successful!');</script>";
                            } else {
                                echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                            }

                            $stmt->close();
                        }
                    }

                    $conn->close();
                }
            }
        }
        ?>

        <!-- Registration Form -->
        <form method="POST" action="RegistrationPage.php">
            <input type="text" name="name" placeholder="Type your name here" required /><br><br>
            <input type="text" name="nic" placeholder="Type your NIC number here" required /><br><br>
            <input type="email" name="email" placeholder="Type your email here" required /><br><br>
            <input type="text" name="mobile" placeholder="Type your mobile number here" required /><br><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
