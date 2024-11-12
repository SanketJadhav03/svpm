<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Option</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #001f3f, #0074d9, #00c1ff); /* Multi-color gradient */
            font-family: 'Arial', sans-serif;
            overflow: hidden; /* Prevent scrolling */
        }

        /* Color splash background effect */
        .color-splash {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
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

        /* 3D container */
        .login-container {
            width: 50%;
            max-width: 400px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent */
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); /* Deep shadow for 3D effect */
            position: relative;
            z-index: 1;
            transition: transform 0.6s ease;
        }

        .login-container:hover {
            transform: perspective(1000px) rotateX(0); /* Remove tilt on hover */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 1.8rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2); /* Text shadow for 3D look */
        }

        .btn {
            width: 48%;
            margin: 10px;
            padding: 0.75rem;
            font-size: 1.2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: linear-gradient(145deg, #0074d9, #005bb5); /* Button gradient */
            border: none;
            border-radius: 10px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.05); /* Elevate and enlarge on hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5); /* Deep shadow on hover */
        }

        .crack-effect {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            pointer-events: none;
            z-index: 10;
            display: none;
        }

        /* Custom input focus */
        .form-control:focus {
            border-color: #0074d9;
            box-shadow: 0 0 10px rgba(0, 116, 217, 0.8); /* Stronger shadow effect */
        }

        /* Animate input field movement */
        .input-move {
            animation: move-animation 0.3s ease;
        }

        /* 3D button hover */
        .login-btn {
            transform-style: preserve-3d; /* Preserve 3D effect */
        }

        /* Keyframes for animation */
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
        
    </style>
</head>
<body>
    <div class="color-splash"></div>
    <div class="login-container text-center" id="login-container">
    <h2><i class="fas fa-user-shield"></i> Select Your Role</h2>
    <div class="role-buttons">
        <a href="admin.php" class="btn btn-lg btn-primary" id="admin-login">Admin</a>
        <a href="principle.php" class="btn btn-lg btn-primary" id="principle-login">Principle</a>
    </div>
    <div class="role-buttons">
        <a href="faculty.php" class="btn btn-lg btn-primary" id="faculty-login">Faculty</a>
        <a href="student.php" class="btn btn-lg btn-primary" id="student-login">Student</a>
        <a href="department.php" class="btn btn-lg btn-primary" id="department-login">Department</a>
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
            splash.style.height = splash.style.width;
            splash.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`; // Random color
            splash.style.left = `${x - size / 2}px`; // Center splash on mouse
            splash.style.top = `${y - size / 2}px`;
            document.querySelector('.color-splash').appendChild(splash);

            setTimeout(() => {
                splash.remove();
            }, 1000);
        };

        document.addEventListener('mousemove', (e) => {
            createSplash(e.clientX, e.clientY);
        });

        setInterval(() => {
            const x = Math.random() * window.innerWidth;
            const y = Math.random() * window.innerHeight;
            createSplash(x, y);
        }, 500); // Auto splashes
    </script>
</body>
</html>
