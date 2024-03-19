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
</head>
<body class="bg-gray-600 font-mono"><br><br><br><br><br><br>
  <div class="container mx-auto mt-8">
    <div class="max-w-md mx-auto bg-gray-500 p-8 border rounded-lg">
      <h2 class="text-center text-2xl font-semibold mb-6 text-green-400">REGISTER</h2>
      
      <form action="" method="post">
        <div class="mb-4 ">
          <select name="user_type" class="border rounded-lg px-4 py-2 w-full">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
          </select>
        </div>
                <div class="mb-4 ">
                    <input type="text" name="id_number" placeholder="ID Number" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <input type="text" name="firstname" placeholder="First Name" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <input type="text" name="lastname" placeholder="Last Name" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <button type="submit" class="bg-gray-700 text-white hover:bg-gray-600 hover:text-green-400 py-2 px-4 rounded-lg">Register</button><br><br>
                <div>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class ="text-white">Already have an account?</a><a href="login.php" class="text-green-400"> Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
