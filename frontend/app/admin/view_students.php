<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>View Registered Students</title>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold text-center mb-6">Students Registered in Hostels</h2>

    <?php
    include '../../../config/db_connect.php';

    // Fetch all hostels
    $hostel_query = "SELECT id, name FROM hostels";
    $hostel_result = mysqli_query($conn, $hostel_query);

    if (mysqli_num_rows($hostel_result) > 0) {
        while ($hostel = mysqli_fetch_assoc($hostel_result)) {
            echo "<h3 class='text-xl font-semibold mb-4'>" . htmlspecialchars($hostel['name']) . "</h3>";

            // Fetch students registered in this hostel and determine the predominant trait
            $student_query = "
              SELECT s.id, s.first_name, s.surname, s.other_name,
                IFNULL(GREATEST(
                  pt.agreeableness, 
                  pt.conscientiousness, 
                  pt.extraversion, 
                  pt.neuroticism, 
                  pt.openness
                ), 'N/A') AS highest_score,
                CASE 
                  WHEN pt.agreeableness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Agreeableness'
                  WHEN pt.conscientiousness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Conscientiousness'
                  WHEN pt.extraversion = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Extraversion'
                  WHEN pt.neuroticism = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Neuroticism'
                  WHEN pt.openness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Openness'
                  ELSE 'No record'
                END AS predominant_trait 
              FROM students s
              LEFT JOIN personality_traits pt ON s.id = pt.student_id
              WHERE s.hostel_id = " . $hostel['id'];

            $student_result = mysqli_query($conn, $student_query);

            if (mysqli_num_rows($student_result) > 0) {
                echo "
                <div class='overflow-x-auto'>
                  <table class='min-w-full bg-white border border-gray-300 rounded-md mb-6'>
                    <thead>
                      <tr class='bg-gray-200'>
                        <th class='px-6 py-3 border-b text-left'>ID</th>
                        <th class='px-6 py-3 border-b text-left'>Name</th>
                        <th class='px-6 py-3 border-b text-left'>Predominant Trait</th>
                        <th class='px-6 py-3 border-b text-left'>Action</th>
                      </tr>
                    </thead>
                    <tbody class='text-left'>";
                while ($student = mysqli_fetch_assoc($student_result)) {
                    // Concatenate surname, first_name, and other_name
                    $full_name = $student['surname'] . " " . $student['first_name'] . " " . $student['other_name'];
                    echo "<tr class='border-b'>
                            <td class='px-6 py-4'>" . htmlspecialchars($student['id']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($full_name) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($student['predominant_trait']) . "</td>
                            <td class='px-6 py-4'>
                              <a href='view_student_profile.php?id=" . $student['id'] . "'>
                                <button class='bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition'>
                                  View Profile
                                </button>
                              </a>
                            </td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo "<p class='text-gray-500 mb-6'>No students registered in this hostel.</p>";
            }
        }
    } else {
        echo "<p class='text-center text-red-500'>No Registered Students yet.</p>";
    }

    mysqli_close($conn);
    ?>

    <div class="text-center mt-6">
      <a href="admin_dashboard.php">
        <button class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition">Back to Dashboard</button>
      </a>
    </div>
  </div>
</body>
</html>
