<?php 

include '../../../backend/assessment/process_result.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Test Results</title>
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        canvas {
            max-width: 1200px;
            max-height: 300px;
        }
    </style>
</head>
<body>
    <h1>Your Personality Test Results</h1>

    <h2>Trait Scores</h2>
    <ul>
        <li>Agreeableness: <?php echo isset($traitScores['agreeableness']) ? $traitScores['agreeableness'] : 'N/A'; ?></li>
        <li>Conscientiousness: <?php echo isset($traitScores['conscientiousness']) ? $traitScores['conscientiousness'] : 'N/A'; ?></li>
        <li>Extraversion: <?php echo isset($traitScores['extraversion']) ? $traitScores['extraversion'] : 'N/A'; ?></li>
        <li>Neuroticism: <?php echo isset($traitScores['neuroticism']) ? $traitScores['neuroticism'] : 'N/A'; ?></li>
        <li>Openness: <?php echo isset($traitScores['openness']) ? $traitScores['openness'] : 'N/A'; ?></li>
    </ul>

    <!-- Chart for overall trait scores -->
    <canvas id="traitChart" width="300" height="150"></canvas>

    <h2>Personality Facet Scores</h2>
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


    <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html"><button>Back to Dashboard</button></a>
    <p>If the personality trait parameters are empty, It means you have not taken the personality assessment test yet. Use the personality assessment button on your <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html">dashboard</a> to take the personality assessment test.</p>

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
