<?php
$db = new SQLite3('sitin.db');

// Fetch purposes for filtering options
$query = $db->query("SELECT DISTINCT purpose FROM sitin_student");
$purposes = [];
while ($row = $query->fetchArray()) {
    $purposes[] = $row['purpose'];
}

// Fetch labs for filtering options
$query = $db->query("SELECT DISTINCT lab FROM sitin_student");
$labs = [];
while ($row = $query->fetchArray()) {
    $labs[] = $row['lab'];
}

$purposeQuery = $db->prepare("
SELECT purpose, COUNT(*) as count, MIN(time_in) as time_in, MIN(time_out) as time_out
FROM sitin_student
GROUP BY purpose
");
$purposeResult = $purposeQuery->execute();

$purposeData = [];
while ($row = $purposeResult->fetchArray(SQLITE3_ASSOC)) {
    $purposeData[] = [
        'label' => $row['purpose'],
        'value' => $row['count'],
        'time_out' => $row['time_out']
    ];
}


$labQuery = $db->prepare("
SELECT lab, COUNT(*) as count, MIN(time_in) as time_in, MIN(time_out) as time_out
FROM sitin_student
GROUP BY lab
");
$labResult = $labQuery->execute();

$labData = [];
while ($row = $labResult->fetchArray(SQLITE3_ASSOC)) {
    $labData[] = [
        'label' => $row['lab'],
        'value' => $row['count'],
        'time_out' => $row['time_out']
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_reports'])) {
    $selected_purpose = $_POST['purpose'] ?? '';
    $selected_lab = $_POST['lab'] ?? '';
    $selected_date = $_POST['date'] ?? '';
    $student_id = $_POST['student_id'] ?? '';

    $query_string = "SELECT id_number, firstname, lastname, purpose, lab, time_in, time_out, status
                     FROM sitin_student
                     WHERE 1";

    if (!empty($selected_purpose)) {
        $query_string .= " AND purpose = :purpose";
    }
    if (!empty($selected_lab)) {
        $query_string .= " AND lab = :lab";
    }
    if (!empty($selected_date)) {
        $query_string .= " AND DATE(time_in) = :selected_date";
    }
    if (!empty($student_id)) {
        $query_string .= " AND id_number = :student_id";
    }

    $query = $db->prepare($query_string);
    if (!empty($selected_purpose)) {
        $query->bindValue(':purpose', $selected_purpose, SQLITE3_TEXT);
    }
    if (!empty($selected_lab)) {
        $query->bindValue(':lab', $selected_lab, SQLITE3_TEXT);
    }
    if (!empty($selected_date)) {
        $query->bindValue(':selected_date', $selected_date, SQLITE3_TEXT);
    }
    if (!empty($student_id)) {
        $query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
    }

    $result = $query->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Reports</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex min-h-screen bg-gray-900 font-mono text-white">

<div class="fixed inset-y-0 w-64 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
  <div class="flex items-center justify-between px-4 mb-6 ">
    <div class="flex items-center">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="flex items-center">
        <a href="admin_dashboard.php">
          <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
        </a>
      </div>
    </div>
    <div>
      <button id="close-menu" class="focus:outline-none">
        <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>
  </div>
  <div class="sidebar-content">
    <ul class="mt-6 bg-gray-600">
      <li>
        <a href="search.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block"><i class="fas fa-search"></i> Search
        </a>
      </li>
      <li>
        <a href="delete_admin.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-trash"></i> Delete
        </a>
      </li>
      <li>
        <a href="view_records.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          <i class="fas fa-eye"></i> View Sitin Records
        </a>
      </li>
      <li>
        <a href="generate_reports.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-file"></i> Generate Reports
        </a>
      </li>
      <li>
        <a href="post_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fa fa-bullhorn"></i> Post Announcements
        </a>
      </li>
      <li>
        <a href="view_feedback.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-comments"></i> Feedbacks and Reporting
        </a>
      </li>
      <li>
        <a href="approval.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-file"></i> Booking Request and Approval
        </a>
      </li>
      <br>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
      </li>
    </ul>
  </div>
</div>
<br><br><br><br>
<div class="flex-1 px-8 py-6">
  <button id="menu-toggle" class="focus:outline-none">
    <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
  </button>
  <div class="flex-1 px-8 py-6">
    <center>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Generate Reports</h2>
    </center>
    <!-- Filter form for generating reports -->
    <div class="mt-8">
      <form method="POST" action="">
        <label for="purpose">Purpose:</label>
        <select class="rounded-lg text-black" id="purpose" name="purpose">
          <option value="">All</option>
          <?php foreach ($purposes as $purpose) : ?>
            <option value="<?php echo htmlspecialchars($purpose); ?>"><?php echo htmlspecialchars($purpose); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="lab">Lab:</label>
        <select class="rounded-lg text-black" id="lab" name="lab">
          <option value="">All</option>
          <?php foreach ($labs as $lab) : ?>
            <option value="<?php echo htmlspecialchars($lab); ?>"><?php echo htmlspecialchars($lab); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="date">Date:</label>
        <input class="rounded-lg text-black" type="date" id="date" name="date">

        <label for="student_id">Student ID:</label>
        <input class="rounded-lg text-black" type="text" id="student_id" name="student_id">

        <button type="submit" name="generate_reports" class="border-solid bg-green-400 text-black text-base rounded-lg">SEARCH</button>

        

      </form>
    </div>

    <!-- Display report table if data is available -->
    <?php if (isset($result)) : ?>
      <div class="mt-6">
        <table class="table-auto w-full text-white">
          <thead>
          <tr>
            <th class="px-4 py-2">ID Number</th>
            <th class="px-4 py-2">First Name</th>
            <th class="px-4 py-2">Last Name</th>
            <th class="px-4 py-2">Purpose</th>
            <th class="px-4 py-2">Lab</th>
            <th class="px-4 py-2">Time In</th>
            <th class="px-4 py-2">Time Out</th>
            <th class="px-4 py-2">Status</th>
          </tr>
          </thead>
          <tbody>
          <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) : ?>
            <tr>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['id_number']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['firstname']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['lastname']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['purpose']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['lab']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['time_in']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['time_out']); ?></td>
              <td class="border px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>



<script>
 



  // Toggle sidebar
  const menuToggle = document.getElementById('menu-toggle');
  const closeMenuButton = document.getElementById('close-menu');
  const sidebar = document.getElementById('sidebar');

  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('w-64');
    if (sidebar.classList.contains('w-64')) {
      sidebar.classList.remove('w-0');
    } else {
      sidebar.classList.add('w-0');
    }
  });

  closeMenuButton.addEventListener('click', () => {
    sidebar.classList.remove('w-64');
    sidebar.classList.add('w-0');
  });

  // Ensure the sidebar is initially collapsed
  document.addEventListener('DOMContentLoaded', function () {
    sidebar.classList.remove('w-64');
    sidebar.classList.add('w-0');
  });
</script>


</body>
</html>