<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Laboratory Sitting Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .hero-image {
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
        }

        @keyframes moveUpDown {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .icon {
            position: absolute;
            animation: moveUpDown 3s ease-in-out infinite;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .headline {
            font-size: 3rem;
            color: #ffffff;
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-in-out;
        }

        .cta-button {
            padding: 1rem 2rem;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #45a049;
        }

        .features {
            padding: 4rem 0;
            text-align: center;
        }

        .feature {
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #4CAF50;
            transition: transform 0.3s ease;
        }

        .feature:hover .feature-icon {
            transform: scale(1.2);
        }

        .feature-title {
            font-size: 1.5rem;
            color: #333333;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #666666;
            font-size: 1.1rem;
        }

        .footer {
            background-color: #333333;
            color: #ffffff;
            text-align: center;
            padding: 2rem 0;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            50% {
                transform: translateX(5px);
            }
            75% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <div class="hero-image bg-gray-500">
        <div class="overlay">
            <h1 class="headline">Welcome to the CSS Laboratory Sitting Monitoring System</h1>
            <button class="cta-button" onclick="window.location.href='login.php';"
                onmouseover="this.style.animation='shake 0.5s ease infinite';"
                onmouseout="this.style.animation='none';">Get Started</button>
            <img src="laptop-icon.png" alt="Laptop Icon" class="icon" style="bottom: 20px; left: 40%;">
            <img src="user-icon.png" alt="User Icon" class="icon" style="bottom: 60%; right: 20px;">
            <img src="laptop-icon.png" alt="Additional Icon 1" class="icon"
                style="top: 10%; left: 50%;">
            <img src="user-icon.png" alt="Additional Icon 2" class="icon"
                style="top: 10%; left: 15%;">
            <img src="user-icon.png" alt="Additional Icon 3" class="icon"
                style="top: 50%; left: 20%;">
            <img src="laptop-icon.png" alt="Additional Icon 5" class="icon"
                style="top: 50%; left: 70%;">
            <img src="laptop-icon.png" alt="Additional Icon 4" class="icon"
                style="top: 70%; left: 5%;">
        </div>
    </div>

    <div class="features bg-gray-300">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-8">Key Features</h2>
            <div class="flex flex-col md:flex-row justify-center ">
                <div class="feature mr-4">
                    <div class="feature-icon">&#128203;</div>
                    <div class="feature-title">Real-time Monitoring</div>
                    <div class="feature-description ">Having a sitin feature for students.</div>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="feature mr-4">
                    <div class="feature-icon">&#128100;</div>
                    <div class="feature-title">Sessions</div>
                    <div class="feature-description">Receive instant sessions for student to use.</div>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="feature">
                    <div class="feature-icon">&#128187;</div>
                    <div class="feature-title">Data Analytics</div>
                    <div class="feature-description">Access insightful analytics to optimize 
                        <br>laboratory usage and improve supervision.</div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 CSS Laboratory Sitting Monitoring System. Melvin P. Sagnoy</p>
    </footer>
    <script>
        // JavaScript to add shaking animation to the button
        document.querySelector('.cta-button').addEventListener('click', function () {
            this.classList.add('shake');
        });
    </script>
</body>

</html>
