<?php
$success_message = '';
$error_message = '';

$db = new SQLite3('sitin.db');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_reports'])) {
  $start_date = $_POST['start_date'] ?? '';
  $end_date = $_POST['end_date'] ?? '';

  $query = $db->prepare("
    SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
    FROM sitin_student s
    JOIN student st ON s.id_number = st.id_number
    WHERE s.time_in BETWEEN :start_date AND :end_date
  ");
  $query->bindValue(':start_date', $start_date, SQLITE3_TEXT);
  $query->bindValue(':end_date', $end_date, SQLITE3_TEXT);

  $result = $query->execute();
} else {
  $query = $db->prepare("
    SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
    FROM sitin_student s
    JOIN student st ON s.id_number = st.id_number
  ");
  $result = $query->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  $student_id = $_POST['student_id'] ?? '';

  $query = $db->prepare("
      UPDATE sitin_student 
      SET time_out = CURRENT_TIMESTAMP, status = 'INACTIVE' 
      WHERE id_number = :student_id
  ");
  $query->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
  $query->execute();

  $query = $db->prepare("
      UPDATE sitin_student
      SET remaining_sessions = remaining_sessions - 1 
      WHERE id_number = :student_id
  ");
  $query->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
  $query->execute();

  $success_message = 'Student logged out successfully.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_in'])) {
  $student_id = $_POST['student_id'] ?? '';

  $query = $db->prepare("
      UPDATE sitin_student
      SET time_in = CURRENT_TIMESTAMP
      WHERE id_number = :student_id
  ");
  $query->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
  $query->execute();

  $success_message = 'Student time in recorded successfully.';
}



// Fetch purpose data for pie chart
$purposeQuery = $db->prepare("
  SELECT purpose, COUNT(*) as count
  FROM sitin_student
  GROUP BY purpose
");
$purposeResult = $purposeQuery->execute();

$purposes = [];
$purposeCounts = [];
while ($row = $purposeResult->fetchArray(SQLITE3_ASSOC)) {
  $purposes[] = $row['purpose'];
  $purposeCounts[] = $row['count'];
}

// Fetch lab data for pie chart
$labQuery = $db->prepare("
  SELECT lab, COUNT(*) as count
  FROM sitin_student
  GROUP BY lab
");
$labResult = $labQuery->execute();

$labs = [];
$labCounts = [];
while ($row = $labResult->fetchArray(SQLITE3_ASSOC)) {
  $labs[] = $row['lab'];
  $labCounts[] = $row['count'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Sitin Records</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .transition-upper-to-lower {
      animation: upper-to-lower 0.7s ease forwards;
    }

    @keyframes upper-to-lower {
      0% {
        opacity: 0;
        transform: translateY(-50%);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    table {
      font-size: 14px;
      max-width: 65%;
      width: 65%;
      table-layout: fixed;
    }

    th,
    td {
      padding: 8px;
    }
  </style>
</head>

<body class="flex min-h-screen bg-gray-800 font-mono text-white">
  <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-700 shadow pt-5 h-screen overflow-auto">
    <div class="flex items-center justify-between px-4 mb-6">
      <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
      </a>
      <button id="close-menu" class="focus:outline-none">
        <svg class="h-6 w-6 hover:text-white-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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

  <div class="flex-1 px-8 py-6">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>
    <center>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Admin Dashboard</h2>
    </center>


    <!-- Filter para sa generate reports -->
    <div class="mt-8">
      <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input class="rounded-lg text-black" type="date" id="start_date" name="start_date">

        <label for="end_date">End Date:</label>
        <input class="rounded-lg text-black" type="date" id="end_date" name="end_date">

        <button type="submit" name="generate_reports" class="border-solid bg-green-400 text-black text-base rounded-lg">SEARCH</button>
        
      </form>
    </div>

    <!-- Intermediate Modal for Selecting Analytics Type -->
    <div id="analyticsOptionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
      <div class="bg-gray-900 bg-opacity-80 absolute inset-0"></div>
      <div class="bg-gray-800 rounded-lg p-8 z-10 text-white shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Select Analytics Type</h2>
          <button id="closeAnalyticsOptionModal" class="focus:outline-none">
            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        
      </div>
    </div>

    <!-- Modal for Data Analytics (Purpose) -->
    <div id="dataAnalyticsModalPurpose" class="fixed inset-0 flex items-center justify-center z-50 hidden">
      <div class="bg-gray-900 bg-opacity-80 absolute inset-0"></div>
      <div class="bg-gray-800 rounded-lg p-8 z-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Data Analytics (Purpose)</h2>
          <button id="closeDataAnalyticsModalPurpose" class="focus:outline-none">
            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        <canvas id="purposeChart"></canvas>
      </div>
    </div>

    <!-- Modal for Data Analytics (Lab) -->
    <div id="dataAnalyticsModalLab" class="fixed inset-0 flex items-center justify-center z-50 hidden">
      <div class="bg-gray-900 bg-opacity-80 absolute inset-0"></div>
      <div class="bg-gray-800 rounded-lg p-8 z-10">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Data Analytics (Lab)</h2>
          <button id="closeDataAnalyticsModalLab" class="focus:outline-none">
            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </svg>
          </button>
        </div>
        <canvas id="labChart"></canvas>
      </div>
    </div>

    <?php if ($result && $result->numColumns() > 0): ?>
      <center>
        <table class="border-collapse table-auto border border-gray-700">
          <thead>
            <tr class="bg-gray-600">
              <th class="px-4 py-2">ID NUMBER</th>
              <th class="px-4 py-2">FIRST NAME</th>
              <th class="px-4 py-2">LAST NAME</th>
              <th class="px-4 py-2">PURPOSE</th>
              <th class="px-4 py-2">LAB</th>
              <th class="px-4 py-2">TIME IN</th>
              <th class="px-4 py-2">TIME OUT</th>
              <th class="px-4 py-2">STATUS</th>
              <th class="px-4 py-2">ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
              <tr class="border-b border-gray-700 text-sm text-gray-400 hover:bg-gray-800 hover:text-white">
                <td class="px-4 py-2"><?php echo $row['id_number']; ?></td>
                <td class="px-4 py-2 capitalize"><?php echo $row['firstname']; ?></td>
                <td class="px-4 py-2 capitalize"><?php echo $row['lastname']; ?></td>
                <td class="px-4 py-2 capitalize"><?php echo $row['purpose']; ?></td>
                <td class="px-4 py-2 capitalize"><?php echo $row['lab']; ?></td>
                <td class="px-4 py-2"><?php echo $row['time_in']; ?></td>
                <td id="time-out-column" class="px-4 py-2"><?php echo $row['time_out']; ?></td>
                <td class="px-4 py-2 text-green-400"><?php echo $row['status']; ?></td>
                <td class="px-4 py-2">
                  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                    <button type="submit" name="logout" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md focus:outline-none">Logout</button>
                  </form>
                </td>
                <td class="px-4 py-2">
        <?php if ($row['status'] === 'accepted'): ?>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                <button type="submit" name="time_in" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Time In</button>
            </form>
        <?php endif; ?>
    </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </center>
    <?php else: ?>
      <p class="text-red-500 text-center">No sitin records found.</p>
    <?php endif; ?>
  </div>

  <!-- JavaScript para sidebar nga toggle -->
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

    // JavaScript for modal functionality
    const viewDataAnalyticsButton = document.getElementById('viewDataAnalytics');
    const analyticsOptionModal = document.getElementById('analyticsOptionModal');
    const closeAnalyticsOptionModal = document.getElementById('closeAnalyticsOptionModal');
    const viewPurposeAnalyticsButton = document.getElementById('viewPurposeAnalytics');
    const viewLabAnalyticsButton = document.getElementById('viewLabAnalytics');

    const dataAnalyticsModalPurpose = document.getElementById('dataAnalyticsModalPurpose');
    const closeDataAnalyticsModalPurpose = document.getElementById('closeDataAnalyticsModalPurpose');

    const dataAnalyticsModalLab = document.getElementById('dataAnalyticsModalLab');
    const closeDataAnalyticsModalLab = document.getElementById('closeDataAnalyticsModalLab');

    viewDataAnalyticsButton.addEventListener('click', () => {
      analyticsOptionModal.classList.remove('hidden');
    });

    closeAnalyticsOptionModal.addEventListener('click', () => {
      analyticsOptionModal.classList.add('hidden');
    });

    viewPurposeAnalyticsButton.addEventListener('click', () => {
      analyticsOptionModal.classList.add('hidden');
      dataAnalyticsModalPurpose.classList.remove('hidden');
      drawPurposeChart();
    });

    viewLabAnalyticsButton.addEventListener('click', () => {
      analyticsOptionModal.classList.add('hidden');
      dataAnalyticsModalLab.classList.remove('hidden');
      drawLabChart();
    });

    closeDataAnalyticsModalPurpose.addEventListener('click', () => {
      dataAnalyticsModalPurpose.classList.add('hidden');
    });

    closeDataAnalyticsModalLab.addEventListener('click', () => {
      dataAnalyticsModalLab.classList.add('hidden');
    });

    // Function to draw the pie chart for Purpose
    function drawPurposeChart() {
      const ctx = document.getElementById('purposeChart').getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode($purposes); ?>,
          datasets: [{
            data: <?php echo json_encode($purposeCounts); ?>,
            backgroundColor: [
              '#FF6384',
              '#36A2EB',
              '#FFCE56',
              '#4BC0C0',
              '#9966FF',
              '#FF9F40'
            ],
            hoverBackgroundColor: [
              '#FF6384',
              '#36A2EB',
              '#FFCE56',
              '#4BC0C0',
              '#9966FF',
              '#FF9F40'
            ]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Purpose of Visits'
            }
          }
        }
      });
    }

    // Function to draw the pie chart for Lab
    function drawLabChart() {
      const ctx = document.getElementById('labChart').getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode($labs); ?>,
          datasets: [{
            data: <?php echo json_encode($labCounts); ?>,
            backgroundColor: [
              '#FF6384',
              '#36A2EB',
              '#FFCE56',
              '#4BC0C0',
              '#9966FF',
              '#FF9F40'
            ],
            hoverBackgroundColor: [
              '#FF6384',
              '#36A2EB',
              '#FFCE56',
              '#4BC0C0',
              '#9966FF',
              '#FF9F40'
            ]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Lab Usage'
            }
          }
        }
      });
    }
  </script>

</body>

</html>
