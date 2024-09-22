<?php 
include '../../../backend/assessment/process_result.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Test Results</title>
    <link href="/roommate-matching-system/frontend/styles/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="/roommate-matching-system/frontend/script/print_page.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-6">
    <div class="container w-full max-w-7xl bg-white p-10 rounded-lg shadow-lg">
        <div class="flex justify-between items-center w-full mb-4">    
            <h1 class="text-2xl font-bold mb-4">Your Personality Test Results</h1> 
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition flex items-center" onclick="printPage()">
            <i class="fas fa-print mr-2"></i> Print This Page
            </button>
        </div>

        <h2 class="text-xl font-semibold mb-2">Trait Scores</h2>
        <ul class="mb-6">
            <li class="mb-1">Agreeableness: <span class="font-medium"><?php echo isset($traitScores['agreeableness']) ? $traitScores['agreeableness'] : 'N/A'; ?></span></li>
            <li class="mb-1">Conscientiousness: <span class="font-medium"><?php echo isset($traitScores['conscientiousness']) ? $traitScores['conscientiousness'] : 'N/A'; ?></span></li>
            <li class="mb-1">Extraversion: <span class="font-medium"><?php echo isset($traitScores['extraversion']) ? $traitScores['extraversion'] : 'N/A'; ?></span></li>
            <li class="mb-1">Neuroticism: <span class="font-medium"><?php echo isset($traitScores['neuroticism']) ? $traitScores['neuroticism'] : 'N/A'; ?></span></li>
            <li class="mb-1">Openness: <span class="font-medium"><?php echo isset($traitScores['openness']) ? $traitScores['openness'] : 'N/A'; ?></span></li>
        </ul>

        <!-- Chart for overall trait scores -->
        <canvas id="traitChart" width=300 height=80></canvas>

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
                    <canvas id="<?php echo $domain; ?>FacetChart" width=300 height=80></canvas>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-6">
            <a href="/roommate-matching-system/frontend/app/user/user_dashboard.php">
                <button class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300">Back to Dashboard</button>
            </a>
        </div>
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
