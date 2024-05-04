<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    #book {
      position: relative;
      perspective: 1000px;
      width: 100lvw; /* Adjust width as needed */
      height: 1000px; /* Adjust height as needed */
      margin: 0 auto;
    }

    .page {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: white;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      padding: 20px;
      box-sizing: border-box;
      border-radius: 10px;
      transition: transform 0.5s ease;
      transform-style: preserve-3d;
      backface-visibility: hidden;
    }

    .page1 {
      z-index: 2;
    }

    .page2, .page3 {
      transform: rotateY(-180deg);
      z-index: 1;
    }

    #book.flip .page1 {
      transform: rotateY(180deg);
    }

    #book.flip .page2 {
      transform: rotateY(0deg);
    }

    #book.flip .page3 {
      transform: rotateY(180deg);
    }
  </style>
</head>

<body class="flex min-h-screen bg-gray-900 font-mono text-white">
  <div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
    <div class="flex items-center justify-between px-4 mb-6 ">
      <div class="flex items-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
      </div>
      <div>
        <button id="close-menu" class="focus:outline-none">
          <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </button>
      </div>
    </div>
    <ul class="mt-6 bg-gray-600">
      <li>
        <a href="profile.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-user"></i> View Profile
        </a>
      </li>
      <li>
        <a href="view_remaining.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-clock"></i> View Remaining Session
        </a>
      </li>
      <li>
        <a href="history.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          <i class="fas fa-history"></i> Sitin Login History
        </a>
      </li>
      <li>
        <a href="feedback.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-thumbs-up"></i> Feedback and Reporting
        </a>
      </li>
      <li>
        <a href="safety.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-thumbs-up"></i> Safety Monitoring/Alert
        </a>
      </li>
      <li>
        <a href="view_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-thumbs-up"></i> View Announcement
        </a>
      </li>
      <li>
        <a href="reservation.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-thumbs-up"></i> Future_reservation
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
      <h1 class="text-4xl font-semibold mb-6 text-green-400">COLLEGE OF INFORMATION & COMPUTER STUDIES CCS</h1>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">LABORATORY RULES AND REGULATIONS</h2>
    </center>

    <div class="container mx-auto relative">
      <div id="book" class="max-w-lg mx-auto bg-white rounded-lg shadow-lg overflow-hidden relative">
        <div id="page1" class="page page1">
          <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Laboratory Rules</h2>
          <div class="text-gray-700">
            <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>

            <ol class="list-decimal pl-6 mb-6">
              <li class="mb-2">Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans, and other personal pieces of equipment must be switched off.</li>
              <li class="mb-2">Games are not allowed inside the lab. This includes computer-related games, card games, and other games that may disturb the operation of the lab.</li>
              <li class="mb-2">Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</li>
              <li class="mb-2">Accessing websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
              <li class="mb-2">Deleting computer files and changing the set-up of the computer is a major offense.</li>
              <li class="mb-2">Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</li>
              <li class="mb-2">Observe proper decorum while inside the laboratory:
                <ul class="list-disc pl-6">
                  <li class="mb-1">Do not enter the lab unless the instructor is present.</li>
                  <li class="mb-1">Deposit all bags, knapsacks, and the likes at the counter.</li>
                  <li class="mb-1">Follow the seating arrangement of your instructor.</li>
                  <li class="mb-1">Close all software programs at the end of class.</li>
                  <li class="mb-1">Return all chairs to their proper places after using.</li>
                </ul>
              </li>
            </ol>
          </div>
        </div>
        <div id="page2" class="page page2">
          <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Laboratory Rules</h2>
          
          <div class="text-gray-700">
            <ol class="list-decimal pl-6 mb-6">
            
              <li class="mb-2">Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
              <li class="mb-2">Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
              <li class="mb-2">Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
              <li class="mb-2">For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
              <li class="mb-2">Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</li>
            </ol>
            <p class="font-semibold mb-2">DISCIPLINARY ACTION</p>
            <ul class="list-disc pl-6 mb-4">
              <li class="mb-1">First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</li>
              <li class="mb-1">Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</li>
          </div>
        </div>
       
      </div>
    </div>
  </div>

  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenuButton = document.getElementById('close-menu');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('w-64'); // Toggle sidebar width
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

    document.getElementById('book').addEventListener('click', function () {
      this.classList.toggle('flip');
    });
  </script>

</body>

</html>
