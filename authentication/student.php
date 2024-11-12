<?php 
session_start();
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/svpm/';
 
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "collegemanagement";   
$conn = new mysqli($servername, $username, $password, $dbname);
 
if (isset($_POST['roll_no']) && isset($_POST['mother_name'])) {
    $roll_no = $_POST['roll_no'];
    $mother_name = $_POST['mother_name'];

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE student_roll = ? AND student_mother_name = ?");
    $stmt->bind_param("ss", $roll_no, $mother_name);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate the credentials
    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Set session variables upon successful login
        $_SESSION['user_role'] = 3; // For student role
        $_SESSION['username'] = $user['student_first_name'];
        $_SESSION['student_id'] = $user['student_id'];

        // Redirect to a dashboard page or home page
        header("Location: $base_url");
        exit();
    } else {
        // Redirect back to the login form with an error message
        $error_message = "Invalid roll no or mother name!";
        header("Location: student.php?error=" . urlencode($error_message));
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Professional Design</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background: linear-gradient(to bottom right, #001f3f, #0074d9); /* Navy blue gradient */
            font-family: 'Arial', sans-serif;
            overflow: hidden; /* Prevent scrolling */
        }

        .color-splash {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1; /* Behind the form */
        }

        .color {
            position: absolute;
            border-radius: 50%;
            pointer-events: none; /* Prevent mouse interactions */
            animation: splash-animation 1s ease-out forwards;
        }

        @keyframes splash-animation {
            0% {
                transform: scale(0);
                opacity: 1;
            }
            100% {
                transform: scale(4);
                opacity: 0;
            }
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1; /* In front of the splash */
            opacity: 0; /* Start with hidden container */
            transform: translateY(-50px); /* Initial position for entrance animation */
            animation: entrance-animation 1s ease forwards; /* Apply entrance animation */
        }

        @keyframes entrance-animation {
            0% {
                opacity: 0;
                transform: translateY(-50px); /* Start above and invisible */
            }
            100% {
                opacity: 1;
                transform: translateY(0); /* End at normal position and fully visible */
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .login-btn {
            width: 100%;
        }

        .filled {
            transform: scale(1.2); /* Scale effect upon successful login */
            transition: transform 0.5s ease; /* Smooth transition */
        }

        .success-message {
            display: none;
            text-align: center;
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px; /* Space above the link */
        }

        .back-to-home {
            margin-top: 20px;
            display: block;
            text-align: center;
            font-size: 0.9rem;
        }

        .back-to-home a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-to-home a:hover {
            color: #0056b3;
        }

        /* Cracking animation styles */
        @keyframes crack-animation {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            25% {
                transform: scale(1.1);
                opacity: 0.9;
            }
            50% {
                transform: scale(0.9);
                opacity: 0.7;
            }
            75% {
                transform: scale(1.1);
                opacity: 0.9;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .crack-effect {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            pointer-events: none; /* Prevent mouse interactions */
            z-index: 10; /* Above the form */
            display: none; /* Hidden by default */
            animation: crack-animation 0.8s ease-in-out forwards; /* Apply crack animation */
        }

        /* Add the move animation */
        @keyframes move-animation {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .input-move {
            animation: move-animation 0.3s ease; /* Apply the move animation */
        }
    </style>
</head>


<body>
<div class="color-splash"></div>
    <div class="login-container" id="login-container">
        <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
        
        <!-- Update form to POST data to login.php -->
        <form id="login-form" method="POST" action="">
            <div class="form-group">
                <label for="username">Roll No</label>
                <input type="text" class="form-control" id="username" name="roll_no" placeholder="Roll No" required>
            </div>
            <div class="form-group">
                <label for="password">Mother Name</label>
                <input type="password" class="form-control" id="password" name="mother_name" placeholder="Mother Name" required>
            </div>
            <button type="submit" class="btn btn-primary login-btn">Login</button>

            <!-- Display any error messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mt-2">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="success-message" id="success-message">Logged in successfully!</div>
        </form>

        <div class="forgot-password">
            <a href="#">Forgot Password?</a>
        </div>
        <div class="back-to-home">
            <a href="<?= $base_url ?>authentication/">← Back to Home</a>
        </div>
        <div class="crack-effect" id="crack-effect"></div> <!-- Crack effect div -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Function to create color splash effect
        const createSplash = (x, y) => {
            const splash = document.createElement('div');
            splash.className = 'color';
            const size = Math.random() * 60 + 30; // Random size for the splash
            splash.style.width = `${size}px`;
            splash.style.height = splash.style.width; // Keep it circular
            splash.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`; // Random color
            splash.style.left = `${x - size / 2}px`; // Center splash on mouse
            splash.style.top = `${y - size / 2}px`;
            document.querySelector('.color-splash').appendChild(splash);

            // Remove splash after animation
            setTimeout(() => {
                splash.remove();
            }, 1000);
        };

        // Mouse move event to create splashes
        document.addEventListener('mousemove', (e) => {
            createSplash(e.clientX, e.clientY);
        });

        // Automatically create splashes at regular intervals
        setInterval(() => {
            const x = Math.random() * window.innerWidth;
            const y = Math.random() * window.innerHeight;
            createSplash(x, y);
        }, 500); // Adjust interval as needed

      
        // Add input animation on focus
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        usernameInput.addEventListener('focus', () => {
            usernameInput.classList.add('input-move');
            setTimeout(() => {
                usernameInput.classList.remove('input-move');
            }, 300);
        });

        passwordInput.addEventListener('focus', () => {
            passwordInput.classList.add('input-move');
            setTimeout(() => {
                passwordInput.classList.remove('input-move');
            }, 300);
        });
    </script>
</body>
</html>
