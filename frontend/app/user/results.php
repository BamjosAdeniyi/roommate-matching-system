<?php 

include '../../../backend/assessment/process_result.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Test Results</title>
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
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

    <h2>Facet Scores</h2>
    <ul>
        <?php foreach ($facetScores as $domain => $facets): ?>
            <li><strong><?php echo ucfirst($domain); ?>:</strong>
                <ul>
                    <?php foreach ($facets as $facet => $score): ?>
                        <li><?php echo htmlspecialchars($facet) . ': ' . htmlspecialchars($score); ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html"><button>Back to Dashboard</button></a>
</body>
</html>
