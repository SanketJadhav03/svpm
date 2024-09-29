<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Professional Design</title>
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
            background: linear-gradient(to bottom right, #b0e0f0,#4a90e2); /* Light blue gradient */
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
            transition: transform 0.5s ease; /* Smooth transition for animation */
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
    </style>
</head>
<body>
    <div class="color-splash"></div>
    <div class="login-container" id="login-container">
        <h2><i class="fas fa-user-graduate"></i> Student Login</h2>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary login-btn">Login</button>
            <div class="success-message" id="success-message">Logged in successfully!</div>
        </form>
        <div class="forgot-password">
            <a href="#">Forgot Password?</a>
        </div>
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

        // Handle form submission
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            const container = document.getElementById('login-container');
            const successMessage = document.getElementById('success-message');

            // Add animation class
            container.classList.add('filled');

            // Show success message after a short delay
            setTimeout(() => {
                successMessage.style.display = 'block';
            }, 500); // Delay for scaling effect
        });
    </script>
</body>
</html>
