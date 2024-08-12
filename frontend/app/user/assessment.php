<?php
include '../../../backend/assessment/questions.php';
include '../../../backend/assessment/choices.php';

// Function to render choices based on question type
function renderChoices($question) {
    global $choices;
    $keyed = $question['keyed'];
    if (!isset($choices[$keyed])) {
        return;
    }
    $choiceList = $choices[$keyed];
    foreach ($choiceList as $choice) {
        echo '<label>';
        echo '<input type="radio" name="responses[' . $question['id'] . ']" value="' . $choice['score'] . '">';
        echo $choice['text'];
        echo '</label><br>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Assessment</title>
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <script src="/roommate-matching-system/frontend/script/assessment.js" defer></script> <!-- Link to JavaScript file -->
    <style>
        .question-container {
            display: none;
        }
        .question-container.active {
            display: block;
        }
        #alertMessage {
            color: red;
            display: none; /* Hidden by default */
            margin-top: 10px; /* Space above the message */
        }
    </style>
</head>
<body>
    <form method="POST" action="/roommate-matching-system/backend/assessment/save_responses.php">
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-container <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                <p id="progress">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></p> <!-- Progress Tracker -->
                <p><?php echo $question['text']; ?></p>
                <?php renderChoices($question); ?>
            </div>
        <?php endforeach; ?>
        <button type="button" id="prevBtn" disabled>Previous</button>
        <button type="button" id="nextBtn">Next</button>
        <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html">
            <button type="button" id="exitBtn">Exit Questionnaire</button>
        </a>
        <button type="submit" id="submitBtn" style="display: none;">Submit</button>
        <div id="alertMessage">Please select an option before proceeding to the next question.</div> <!-- Alert Message -->
    </form>
    
    <!-- Hidden input to pass questions data to JavaScript -->
    <script type="application/json" id="questionsData"><?php echo json_encode($questions); ?></script>

</body>
</html>