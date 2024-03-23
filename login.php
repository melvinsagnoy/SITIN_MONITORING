<?php
session_start();
$database = 'sitin.db';
$conn = new SQLite3($database);
if (!$conn) {
  die("Connection failed: " . $conn->lastErrorMsg());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_type = $_POST['user_type'];
  $id_number = $_POST['id_number'];
  $password = $_POST['password'];


  $table_name = ($user_type === 'student') ? 'student' : 'admin';

  $query = "SELECT * FROM $table_name WHERE id_number=:id_number";
  $stmt = $conn->prepare($query);
  $stmt->bindValue(':id_number', $id_number, SQLITE3_TEXT);
  $result = $stmt->execute();

  if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['id_number'] = $id_number;
 
      if ($user_type === 'student') {
        header("Location: student_dashboard.php");
      } else { 
        header("Location: admin_dashboard.php");
      }
      exit(); 
    }
  }

 
  echo "<script>
        alert('Invalid ID number or password!');
        window.location.href = 'login.php';
      </script>";
  exit(); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex min-h-screen ">
  <div class="w-1/2 bg-gray-600 p-8 text-5xl font-mono">
    <br><br><br>
    <h1 class="text-white font-mono">Unlocking Insights, Optimizing Resources: Introducing the <h1 class = "text-green-400 font-mono">CCS SITIN</h1><h1 class = "text-white">Monitoring System</h1>
  </div>

  <div class="w-1/2 flex items-center font-mono bg-gray-600" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <div class="w-3/5 bg-gray-500 p-8 border rounded-lg">
      <h2 class="text-2xl font-semibold mb-6 text-white">Login As</h2>
      <form action="" method="post">
        <div class="mb-4">
          <select name="user_type" class="border rounded-lg px-4 py-2 w-full bg-gray-200 hover:bg-gray-500 hover:text-white">
            <option value="student" >Student</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="mb-4">
          <input type="text" name="id_number" placeholder="ID Number" class="border rounded-lg px-4 py-2 w-full">
        </div>
        <div class="mb-4">
          <input type="password" name="password" placeholder="Password" class="border rounded-lg px-4 py-2 w-full">
        </div>
        <button type="submit" class="bg-gray-700 hover:bg-gray-400 hover:text-green-400 text-white py-2 px-4 rounded-lg">Login</button>
        <div class="text-center mt-4">
          <a class="text-white ">Don't have an account?</a> <a href="register.php" class="text-green-400">Register</a>
        </div>
      </form>
    </div>
  </
