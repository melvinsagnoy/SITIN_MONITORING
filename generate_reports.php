<?php
$db = new SQLite3('sitin.db');

$query = $db->query("SELECT DISTINCT purpose FROM sitin_student");
$purposes = [];
while ($row = $query->fetchArray()) {
    $purposes[] = $row['purpose'];
}

$query = $db->query("SELECT DISTINCT lab FROM sitin_student");
$labs = [];
while ($row = $query->fetchArray()) {
    $labs[] = $row['lab'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_reports'])) {
    $selected_purpose = $_POST['purpose'] ?? '';
    $selected_lab = $_POST['lab'] ?? '';
    $selected_date = $_POST['date'] ?? '';
    $student_id = $_POST['student_id'] ?? '';

    $query_string = "SELECT id_number, firstname, lastname, purpose, lab, time_in, time_out, status
                     FROM sitin_student
                     WHERE 1";

    // Add conditions based on selected filters
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

    // Prepare and bind parameters
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
</head>
<body class="flex min-h-screen bg-gray-800 font-mono text-white">

 
    <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-700 shadow pt-5 h-screen overflow-auto ">
        
        <div class="flex items-center justify-between px-4 mb-6">
        <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
    </a>
            <button id="close-menu" class="focus:outline-none">
                <svg class="h-6 w-6 hover:text-white-200 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
     
        <ul class="space-y-2 px-4">
        <li>
        <a href="search.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block"><i class="fas fa-search"></i> Search
        </a>
      </li>
      <li>
        <a href="delete_admin.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class = "fas fa-trash"></i>  Delete
        </a>
      </li>
      <li>
        <a href="view_records.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          <i class = "fas fa-eye"></i> View Sitin Records
        </a>
      </li>
      <li>
        <a href="generate_reports.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class = "fas fa-file"></i> Generate Reports
        </a>
      </li>
      <br>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class = "fas fa-sign-out-alt"></i> Log Out
        </a>
      </li>
        </ul>
    </div>

    <!--Main-->
    <div class="flex-1 px-8 py-6 ">
    <button id="menu-toggle" class="focus:outline-none">
            <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <center>
            <h1 class="text-4xl font-semibold mb-6 text-green-400">Generate Reports</h1>
        </center>

    <form method="POST" action="" class="mt-8">
    <div class="flex flex-row space-x-4">
        <label for="purpose" class="text-green-400">Purpose:</label>
        <select id="purpose" name="purpose" class="rounded-lg bg-gray-600 text-white px-1 py-2 h-10">
            <option value="">Select Purpose</option>
            <?php foreach ($purposes as $purpose): ?>
                <option value="<?php echo $purpose; ?>"><?php echo $purpose; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="lab" class="text-green-400">Lab:</label>
        <select id="lab" name="lab" class="rounded-lg bg-gray-600 text-white px-2 py-1 h-10">
            <option value="">Select Lab</option>
            <?php foreach ($labs as $lab): ?>
                <option value="<?php echo $lab; ?>"><?php echo $lab; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="student_id" class="text-green-400">Student ID:</label>
<input type="text" id="student_id" name="student_id" class="rounded-lg bg-gray-600 text-white px-2 py-1 h-10" placeholder="Enter Student ID">
        <label for="date" class="text-green-400">Select Date:</label>
        <input type="date" id="date" name="date" class="rounded-lg bg-gray-600 text-white px-2 py-1 h-10">

        <button type="submit" name="generate_reports" class="border-solid bg-green-400 text-black text-base rounded-lg px-4 py-2 h-10">Generate Reports</button>
    </div>
</form>


<?php if (isset($result)): ?>
    <br>


    <div id="reportTable" class="overflow-x-auto">
        <p class="font-semibold text-white" >Date:</p><p><?php echo date('F j, Y', strtotime($selected_date)); ?></p>
    <table class="table-auto w-full shadow-md rounded-md overflow-x-auto transition-upper-to-lower mt-4">
            <thead>
                <tr class="text-xs font-medium text-left text-white bg-gray-700 uppercase">
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
                <?php while ($row = $result->fetchArray()): ?>
                    <tr class="border-b border-gray-700 text-sm text-gray-400 hover:bg-gray-800 hover:text-white">
                        <td class="px-4 py-2"><?php echo $row['id_number']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['firstname']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['lastname']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['purpose']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['lab']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['time_in']; ?></td>
                        <td class="px-4 py-2"><?php echo $row['time_out']; ?></td>
                        <td class="px-4 py-2 text-green-400"><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<br>
<div class="flex justify-end mb-4">
    <div class="relative inline-block text-left">
        <button id="exportButton" type="button" class="inline-flex justify-center w-36 px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
            Export
            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 0 0-1.06-1.06L6 7.94V1.75a.75.75 0 0 0-1.5 0V7.94L2.47 4.47a.75.75 0 0 0-1.06 1.06l4.25 4.25a.75.75 0 0 0 1.06 0l4.25-4.25a.75.75 0 0 0 0-1.06zm0 14a.75.75 0 0 0-1.06 1.06l4.25 4.25a.75.75 0 0 0 1.06 0l4.25-4.25a.75.75 0 0 0-1.06-1.06L12 17.06V11.5a.75.75 0 0 0-1.5 0v5.56l-2.47-2.47a.75.75 0 0 0-1.06 1.06l4.25 4.25a.75.75 0 0 0 1.06 0l4.25-4.25a.75.75 0 0 0 0-1.06L11.06 17.06a.75.75 0 0 0-.06.94zM15.25 6.25a.75.75 0 0 0-1.5 0v5.56l-2.47-2.47a.75.75 0 0 0-1.06 1.06l4.25 4.25a.75.75 0 0 0 1.06 0l4.25-4.25a.75.75 0 0 0-1.06-1.06L15.25 11.81V6.25z" clip-rule="evenodd"/>
            </svg>
        </button>
        <div id="exportOptions" class="absolute right-0 mt-2 w-36 bg-gray-600 rounded-md shadow-lg origin-top-right ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                <button id="exportToExcel" type="button" class="block px-4 py-2 text-sm text-white hover:bg-gray-700" role="menuitem">Export to Excel</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        document.getElementById('exportOptions').style.display = 'block';
    });



    document.getElementById('exportToExcel').addEventListener('click', function() {
        const wb = XLSX.utils.table_to_book(document.getElementById('reportTable'));
        XLSX.writeFile(wb, 'reports.xlsx');
    });
</script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.remove('w-64');
      sidebar.classList.add('w-0');
    });

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
  </script>
</body>
</html>
                    