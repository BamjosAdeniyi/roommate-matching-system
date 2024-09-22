<?php 
include '../../../backend/assessment/process_result.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Test Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/roommate-matching-system/frontend/styles/style.css" rel="stylesheet">
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
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-7xl bg-white p-10 rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-4">Your Personality Test Results</h1>
        <button onclick="printPage()" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 mb-6">Print This Page</button>

        <h2 class="text-xl font-semibold mb-2">Trait Scores</h2>
        <ul class="mb-6">
            <li class="mb-1">Agreeableness: <span class="font-medium"><?php echo isset($traitScores['agreeableness']) ? $traitScores['agreeableness'] : 'N/A'; ?></span></li>
            <li class="mb-1">Conscientiousness: <span class="font-medium"><?php echo isset($traitScores['conscientiousness']) ? $traitScores['conscientiousness'] : 'N/A'; ?></span></li>
            <li class="mb-1">Extraversion: <span class="font-medium"><?php echo isset($traitScores['extraversion']) ? $traitScores['extraversion'] : 'N/A'; ?></span></li>
            <li class="mb-1">Neuroticism: <span class="font-medium"><?php echo isset($traitScores['neuroticism']) ? $traitScores['neuroticism'] : 'N/A'; ?></span></li>
            <li class="mb-1">Openness: <span class="font-medium"><?php echo isset($traitScores['openness']) ? $traitScores['openness'] : 'N/A'; ?></span></li>
        </ul>

        <!-- Chart for overall trait scores -->
        <canvas id="traitChart" width="150" height="75"></canvas>

        <h2 class="text-xl font-semibold mt-8 mb-2">Personality Facet Scores</h2>
        <ul>
            <?php foreach ($facetScores as $domain => $facets): ?>
                <li class="mb-4">
                    <strong class="text-lg"><?php echo ucfirst($traitNames[$domain]); ?>:</strong>
                    <ul class="ml-4">
                        <?php foreach ($facets as $facet => $score): ?>
                            <li><?php echo htmlspecialchars($facet) . ': ' . htmlspecialchars($score); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Chart for individual trait facets -->
                    <canvas id="<?php echo $domain; ?>FacetChart" max-width="50" max-height="25"></canvas>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-6">
            <a href="/roommate-matching-system/frontend/app/user/user_dashboard.php">
                <button class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300">Back to Dashboard</button>
            </a>
        </div>

        <p class="mt-4 text-gray-600">
            If the personality trait parameters are empty, it means you have not taken the personality assessment test yet. Use the personality assessment button on your 
            <a href="/roommate-matching-system/frontend/app/user/user_dashboard.php" class="text-blue-500 underline">dashboard</a> to take the personality assessment test.
        </p>
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
