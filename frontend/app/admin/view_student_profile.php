<?php 
include '../../../backend/assessment/process_result_admin.php';

// The student ID is now correctly retrieved from the URL
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($student_id == 0) {
    die("Invalid student ID.");
}

include '../../../config/db_connect.php';

$student_query = "
    SELECT s.first_name, s.surname, s.other_name, h.name AS hostel_name
    FROM students s
    JOIN hostels h ON s.hostel_id = h.id
    WHERE s.id = $student_id";

$student_result = mysqli_query($conn, $student_query);

if (mysqli_num_rows($student_result) > 0) {
    $student = mysqli_fetch_assoc($student_result);
} else {
    die("Student not found.");
}

$full_name = $student['surname'] . " " . $student['first_name'] . " " . $student['other_name'];
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($student['first_name']); ?>'s Profile</title>
    <link href="/roommate-matching-system/frontend/styles/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="/roommate-matching-system/frontend/script/print_page.js"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 flex flex-col items-center">
    <div class="container w-full max-w-7xl bg-white p-10 rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($full_name); ?>'s Profile</h1>
        <p class="text-gray-700"><strong>Hostel:</strong> <?php echo htmlspecialchars($student['hostel_name']); ?></p>
        <button class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4 hover:bg-blue-600 transition" onclick="printPage()">Print This Page</button>

        <h2 class="text-xl font-semibold mt-6">Personality Test Results</h2>

        <h3 class="text-lg font-medium mt-4">Trait Scores</h3>
        <ul class="list-disc ml-6">
            <li>Agreeableness: <?php echo isset($traitScores['agreeableness']) ? $traitScores['agreeableness'] : 'N/A'; ?></li>
            <li>Conscientiousness: <?php echo isset($traitScores['conscientiousness']) ? $traitScores['conscientiousness'] : 'N/A'; ?></li>
            <li>Extraversion: <?php echo isset($traitScores['extraversion']) ? $traitScores['extraversion'] : 'N/A'; ?></li>
            <li>Neuroticism: <?php echo isset($traitScores['neuroticism']) ? $traitScores['neuroticism'] : 'N/A'; ?></li>
            <li>Openness: <?php echo isset($traitScores['openness']) ? $traitScores['openness'] : 'N/A'; ?></li>
        </ul>

        <!-- Chart for overall trait scores -->
        <div class="my-6">
            <canvas id="traitChart" width="300" height="80"></canvas>
        </div>

        <h3 class="text-lg font-medium mt-6">Personality Facet Scores</h3>
        <ul class="list-disc ml-6">
            <?php foreach ($facetScores as $domain => $facets): ?>
                <li class="mb-4"><strong><?php echo ucfirst($traitNames[$domain]); ?>:</strong>
                    <ul class="list-square ml-6">
                        <?php foreach ($facets as $facet => $score): ?>
                            <li><?php echo htmlspecialchars($facet) . ': ' . htmlspecialchars($score); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Chart for individual trait facets -->
                    <div class="my-6">
                        <canvas id="<?php echo $domain; ?>FacetChart" width="300" height="80"></canvas>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="/roommate-matching-system/frontend/app/admin/view_students.php">
            <button class="bg-gray-500 text-white px-4 py-2 rounded-md mt-4 hover:bg-gray-600 transition">Back to Students</button>
        </a>
    </div>

    <script>
        const traitScores = {
            agreeableness: <?php echo $traitScores['agreeableness']; ?>,
            conscientiousness: <?php echo $traitScores['conscientiousness']; ?>,
            extraversion: <?php echo $traitScores['extraversion']; ?>,
            neuroticism: <?php echo $traitScores['neuroticism']; ?>,
            openness: <?php echo $traitScores['openness']; ?>
        };

        const facetScores = <?php echo json_encode($facetScores); ?>;
        const traitNames = <?php echo json_encode($traitNames); ?>;
    </script>
    <script src="/roommate-matching-system/frontend/script/result-charts.js"></script>
</body>
</html>
