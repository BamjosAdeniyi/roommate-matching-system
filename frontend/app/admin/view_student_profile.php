<?php 
include '../../../backend/assessment/process_result.php';

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

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($student['surname'], $student['first_name'], $student['other_name']); ?>'s Profile</title>
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="/roommate-matching-system/frontend/script/print_page.js"></script>
    <style>
        canvas {
            max-width: 1200px;
            max-height: 300px;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($student['name']); ?>'s Profile</h1>
    <p><strong>Hostel:</strong> <?php echo htmlspecialchars($student['hostel_name']); ?></p>
    <button onclick="printPage()">Print This Page</button>

    <h2>Personality Test Results</h2>

    <h3>Trait Scores</h3>
    <ul>
        <li>Agreeableness: <?php echo isset($traitScores['agreeableness']) ? $traitScores['agreeableness'] : 'N/A'; ?></li>
        <li>Conscientiousness: <?php echo isset($traitScores['conscientiousness']) ? $traitScores['conscientiousness'] : 'N/A'; ?></li>
        <li>Extraversion: <?php echo isset($traitScores['extraversion']) ? $traitScores['extraversion'] : 'N/A'; ?></li>
        <li>Neuroticism: <?php echo isset($traitScores['neuroticism']) ? $traitScores['neuroticism'] : 'N/A'; ?></li>
        <li>Openness: <?php echo isset($traitScores['openness']) ? $traitScores['openness'] : 'N/A'; ?></li>
    </ul>

    <!-- Chart for overall trait scores -->
    <canvas id="traitChart" width="300" height="150"></canvas>

    <h3>Personality Facet Scores</h3>
    <ul>
        <?php foreach ($facetScores as $domain => $facets): ?>
            <li><strong><?php echo ucfirst($traitNames[$domain]); ?>:</strong>
                <ul>
                    <?php foreach ($facets as $facet => $score): ?>
                        <li><?php echo htmlspecialchars($facet) . ': ' . htmlspecialchars($score); ?></li>
                    <?php endforeach; ?>
                </ul>
                <!-- Chart for individual trait facets -->
                <canvas id="<?php echo $domain; ?>FacetChart" width="300" height="150"></canvas>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="/roommate-matching-system/frontend/app/admin/view_students.php"><button>Back to Students</button></a>

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
