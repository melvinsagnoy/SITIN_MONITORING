<?php

$database = 'sitin.db';
$conn = new SQLite3($database);
if (!$conn) {
  die("Connection failed: " . $conn->lastErrorMsg());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_type = $_POST['user_type'];
  $id_number = $_POST['id_number'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


  $query = "SELECT * FROM $user_type WHERE email=:email";
  $stmt = $conn->prepare($query);
  $stmt->bindValue(':email', $email, SQLITE3_TEXT);
  $result = $stmt->execute();

  if ($result->fetchArray(SQLITE3_ASSOC)) {
    echo "User already exists!"; 
  } else {

    $query = "INSERT INTO $user_type (id_number, firstname, lastname, email, password) VALUES (:id_number, :firstname, :lastname, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':id_number', $id_number, SQLITE3_TEXT);
    $stmt->bindValue(':firstname', $firstname, SQLITE3_TEXT);
    $stmt->bindValue(':lastname', $lastname, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);

    if ($stmt->execute()) {
      
      $conn->close();
?>

<script>
  alert("Registration successful!");
  
  window.location.href = "login.php";
</script>

<?php
    } else {
      echo "Error: Registration failed!";
    }
  }
}


$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .popup {
            animation: pop-up 0.5s ease forwards;
        }

        @keyframes pop-up {
            0% {
                opacity: 0;
                transform: translateY(-50%);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes hump-wave {
            0% {
                transform: translateY(0);
            }

            25% {
                transform: translateY(-10px);
            }

            50% {
                transform: translateY(0);
            }

            75% {
                transform: translateY(10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .hump-wave-animation {
            animation: hump-wave 2s infinite;
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-900 text-white">

    <div class="w-1/2 bg-gray-800 p-8 text-5xl font-mono">
        <br><br><br><br><br>
        <h1 class="text-green-400 font-mono hump-wave-animation">Unlocking Insights, Optimizing Resources:</h1>
        <h1 class="text-white hump-wave-animation">Introducing the CCS SITIN Monitoring System</h1>
    </div>

    <div class="w-1/2 flex items-center font-mono bg-gray-800">
        <div class="w-3/5 bg-gray-700 p-8 border rounded-lg mx-auto popup">
            <h2 class="text-2xl font-semibold mb-6 text-green-400 text-center">REGISTER</h2>
            <form action="" method="post">
                <div class="mb-4 ">
                    <select name="user_type" class="border rounded-lg px-4 py-2 w-full bg-gray-600 hover:bg-gray-500 hover:text-white">
                        <option value="student" class="text-white">Student</option>
                        <option value="admin" class="text-white">Admin</option>
                    </select>
                </div>
                <div class="mb-4 ">
                    <input type="text" name="id_number" placeholder="ID Number" class="border rounded-lg px-4 py-2 w-full bg-gray-600 text-white">
                </div>
                <div class="mb-4">
                    <input type="text" name="firstname" placeholder="First Name" class="border rounded-lg px-4 py-2 w-full bg-gray-600 text-white">
                </div>
                <div class="mb-4">
                    <input type="text" name="lastname" placeholder="Last Name" class="border rounded-lg px-4 py-2 w-full bg-gray-600 text-white">
                </div>
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" class="border rounded-lg px-4 py-2 w-full bg-gray-600 text-white">
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="border rounded-lg px-4 py-2 w-full bg-gray-600 text-white">
                </div>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-gray-900 py-2 px-4 rounded-lg w-full">Register</button><br><br>
                <div class="text-center mt-4">
                    <span class="text-white">Already have an account?</span> <a href="login.php" class="text-green-400"> Login</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
