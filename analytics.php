<?php
$db = new SQLite3('sitin.db');

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fetch purposes and labs for the form
$purposes = [];
$labs = [];
$query = $db->query("SELECT DISTINCT purpose FROM sitin_student");
while ($row = $query->fetchArray()) {
    $purposes[] = $row['purpose'];
}
$query = $db->query("SELECT DISTINCT lab FROM sitin_student");
while ($row = $query->fetchArray()) {
    $labs[] = $row['lab'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['filter_date'])) {
        $selected_date = $_POST['selected_date'] ?? '';

        // Fetch purpose data filtered by date
        $purposeQuery = $db->prepare("
            SELECT purpose, COUNT(*) as count, MIN(time_in) as time_in, MIN(time_out) as time_out
            FROM sitin_student
            WHERE DATE(time_in) = :selected_date
            GROUP BY purpose
        ");
        $purposeQuery->bindValue(':selected_date', $selected_date, SQLITE3_TEXT);
        $purposeResult = $purposeQuery->execute();

        $purposeData = [];
        while ($row = $purposeResult->fetchArray(SQLITE3_ASSOC)) {
            $purposeData[] = [
                'label' => $row['purpose'],
                'value' => $row['count'],
                'time_out' => $row['time_out']
            ];
        }

        // Fetch lab data filtered by date
        $labQuery = $db->prepare("
            SELECT lab, COUNT(*) as count, MIN(time_in) as time_in, MIN(time_out) as time_out
            FROM sitin_student
            WHERE DATE(time_in) = :selected_date
            GROUP BY lab
        ");
        $labQuery->bindValue(':selected_date', $selected_date, SQLITE3_TEXT);
        $labResult = $labQuery->execute();

        $labData = [];
        while ($row = $labResult->fetchArray(SQLITE3_ASSOC)) {
            $labData[] = [
                'label' => $row['lab'],
                'value' => $row['count'],
                'time_out' => $row['time_out']
            ];
        }

        echo json_encode(['purposeData' => $purposeData, 'labData' => $labData]);
        exit();
    } 
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
  <style>
    .center-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Full height of viewport */
    }

    /* CSS styles for the button */
    #viewAnalyticsBtn {
      padding: 20px 40px; /* Adjust padding to increase button size */
      font-size: 2rem; /* Increase font size */
      color: white; /* Change icon color to white */
    }
  </style>
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
        <li>
    <a href="analytics.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class="fas fa-chart-pie"></i> Daily Analytics
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
      <h2 class="text-4xl font-semibold mb-6 text-green-400">DAILY ANALYTICS</h2>
    </center>
    <!-- Filter form for generating reports -->
    <div class="center-container">
    <div class="center-container">
    <form method="POST" action="">
        <button type="button" id="viewAnalyticsBtn" class="border-solid bg-blue-400 text-black text-base rounded-lg ml-4 flex items-center justify-center"> <!-- Center the icon horizontally -->
            <i class="fas fa-chart-pie fa-5x mr-2"></i> <!-- Make the icon bigger -->
            
        </button>
    </form>
</div>
</div>

     



<div id="analyticsModal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-700 rounded-lg p-8 hidden">
  <button id="closeAnalyticsModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
  <h2 class="text-2xl mb-4">Select Analytics Option</h2>
  <form id="analyticsForm" method="POST" action="">
    <input type="radio" id="labOption" name="analytics_option" value="lab">
    <label for="labOption">Lab</label><br>
    <input type="radio" id="purposeOption" name="analytics_option" value="purpose">
    <label for="purposeOption">Purpose</label><br><br>
    <input type="hidden" id="filterOption" name="filter_option"> <!-- New hidden input -->
    <button type="button" id="submitAnalyticsBtn" class="bg-blue-500 text-white py-2 px-4 rounded">Generate Daily Analytics</button>
  </form>
</div>

<!-- Modal for displaying analytics chart -->
<div id="chartModal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-700 rounded-lg p-8 hidden">
  <button id="closeChartModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
  <h2 class="text-2xl mb-4">Analytics Chart</h2>
  <label for="analyticsDate" >Date:</label>
  <input type="date" id="analyticsDate" name="analytics_date" class="text-black">
  <button type="button" id="filterDateBtn" class="border-solid bg-blue-400 text-black text-base rounded-lg ml-4">Filter Date</button>
  <canvas id="chartCanvas" class="text-white"></canvas>
</div>

<script>
 document.addEventListener('DOMContentLoaded', function() {
  const viewAnalyticsBtn = document.getElementById('viewAnalyticsBtn');
  const analyticsModal = document.getElementById('analyticsModal');
  const submitAnalyticsBtn = document.getElementById('submitAnalyticsBtn');
  const closeAnalyticsModalBtn = document.getElementById('closeAnalyticsModal');
  const closeChartModalBtn = document.getElementById('closeChartModal');
  const filterDateBtn = document.getElementById('filterDateBtn');
  const analyticsDateInput = document.getElementById('analyticsDate');
  const menuToggle = document.getElementById('menu-toggle');
  const closeMenuButton = document.getElementById('close-menu');
  const sidebar = document.getElementById('sidebar');

  // Sidebar toggle
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
  sidebar.classList.remove('w-64');
  sidebar.classList.add('w-0');

  // View Analytics Button
  viewAnalyticsBtn.addEventListener('click', () => {
    analyticsModal.classList.remove('hidden');
  });

  // Submit Analytics Button
  submitAnalyticsBtn.addEventListener('click', async () => {
    const selectedOption = document.querySelector('input[name="analytics_option"]:checked');
    if (selectedOption) {
      const filterOption = selectedOption.value;
      const selectedDate = analyticsDateInput.value;
      const data = await filterDataByDate(selectedDate);

      let chartData = [];
      if (filterOption === 'purpose') {
        chartData = data.purposeData;
      } else if (filterOption === 'lab') {
        chartData = data.labData;
      }
      
      createPieChart(chartData);
      analyticsModal.classList.add('hidden');
    } else {
      alert('Please select an option.');
    }
  });

  // Close Analytics Modal
  closeAnalyticsModalBtn.addEventListener('click', () => {
    analyticsModal.classList.add('hidden');
  });

  // Close Chart Modal
  closeChartModalBtn.addEventListener('click', () => {
    const chartModal = document.getElementById('chartModal');
    chartModal.classList.add('hidden');
  });

  // Filter Date Button
  filterDateBtn.addEventListener('click', async function() {
    const selectedDate = analyticsDateInput.value;
    const data = await filterDataByDate(selectedDate);
    
    let filteredData = [];
    const filterOption = document.querySelector('input[name="analytics_option"]:checked').value;
    if (filterOption === 'purpose') {
      filteredData = data.purposeData;
    } else if (filterOption === 'lab') {
      filteredData = data.labData;
    }
    
    createPieChart(filteredData);
  });

  // Filter Data by Date function
  async function filterDataByDate(selectedDate) {
    const response = await fetch('', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        'filter_date': true,
        'selected_date': selectedDate
      })
    });

    const result = await response.json();
    return result;
  }

  // Create Pie Chart function
  function createPieChart(data) {
    const chartCanvas = document.getElementById('chartCanvas');
    const ctx = chartCanvas.getContext('2d');
    if (window.analyticsChart) {
      window.analyticsChart.destroy();
    }
    window.analyticsChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: data.map(item => item.label),
        datasets: [{
          data: data.map(item => item.value),
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
        }],
      },
    });
    const chartModal = document.getElementById('chartModal');
    chartModal.classList.remove('hidden');
  }

  // Form submission for generating reports
  document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('generate_reports.php', {
      method: 'POST',
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      const { purposeData, labData } = data;

      // Update Purpose Chart
      const purposeChartCanvas = document.getElementById('purposeChart').getContext('2d');
      if (window.purposeChart) {
        window.purposeChart.destroy();
      }
      window.purposeChart = new Chart(purposeChartCanvas, {
        type: 'bar',
        data: {
          labels: purposeData.map(item => item.label),
          datasets: [{
            label: 'Count',
            data: purposeData.map(item => item.value),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
          }],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });

      // Update Lab Chart
      const labChartCanvas = document.getElementById('labChart').getContext('2d');
      if (window.labChart) {
        window.labChart.destroy();
      }
      window.labChart = new Chart(labChartCanvas, {
        type: 'bar',
        data: {
          labels: labData.map(item => item.label),
          datasets: [{
            label: 'Count',
            data: labData.map(item => item.value),
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1,
          }],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    })
    .catch(error => console.error('Error:', error));
  });
});
</script>

</body>
</html>


