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
        echo '<label class="block">';
        echo '<input type="radio" name="responses[' . $question['id'] . ']" value="' . $choice['score'] . '" class="mr-2">'; // Added margin
        echo $choice['text'];
        echo '</label>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl bg-white p-10 rounded-lg shadow-lg"> <!-- Increased max-width -->
        <form method="POST" action="/roommate-matching-system/backend/assessment/save_responses.php">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-container <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                    <p class="text-xl font-semibold mb-6" id="progress">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></p> <!-- Progress Tracker -->
                    <p class="mb-6 text-lg leading-relaxed"><?php echo $question['text']; ?></p> <!-- Increased font size and line height -->
                    <?php renderChoices($question); ?>
                </div>
            <?php endforeach; ?>
            <div class="flex justify-evenly mt-8">
                <button type="button" id="prevBtn" class="bg-gray-300 text-gray-700 py-3 px-6 rounded-md" disabled>Previous</button>
                <button type="button" id="nextBtn" class="bg-blue-500 text-white py-3 px-6 rounded-md hover:bg-blue-600 transition duration-300">Next</button>
                <button type="submit" id="submitBtn" class="bg-green-500 text-white py-3 px-6 rounded-md" style="display: none;">Submit</button>
                <a href="/roommate-matching-system/frontend/app/user/user_dashboard.php">
                    <button type="button" id="exitBtn" class="bg-gray-300 text-gray-700 py-3 px-6 rounded-md">Exit Questionnaire</button>
                </a>
            </div>
            <div id="alertMessage" class="mt-4 text-red-500">Please select an option before proceeding to the next question.</div> <!-- Alert Message -->
        </form>
        
        <!-- Hidden input to pass questions data to JavaScript -->
        <script type="application/json" id="questionsData"><?php echo json_encode($questions); ?></script>
    </div>
</body>
</html>

