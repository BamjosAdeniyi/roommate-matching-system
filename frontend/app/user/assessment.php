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
    <style>
        .question-container {
            display: none;
        }
        .question-container.active {
            display: block;
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
    </form>

    <script>
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const questionContainers = document.querySelectorAll('.question-container');
        const progressDisplay = document.getElementById('progress');
        let currentIndex = 0;

        function updateProgress() {
            progressDisplay.textContent = `Question ${currentIndex + 1} of ${questionContainers.length}`; // Update progress text
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                questionContainers[currentIndex].classList.remove('active');
                currentIndex--;
                questionContainers[currentIndex].classList.add('active');
            }
            updateButtons();
            updateProgress(); // Update progress when navigating
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < questionContainers.length - 1) {
                questionContainers[currentIndex].classList.remove('active');
                currentIndex++;
                questionContainers[currentIndex].classList.add('active');
            }
            updateButtons();
            updateProgress(); // Update progress when navigating
        });

        function updateButtons() {
            prevBtn.disabled = currentIndex === 0;
            nextBtn.style.display = currentIndex === questionContainers.length - 1 ? 'none' : 'inline';
            submitBtn.style.display = currentIndex === questionContainers.length - 1 ? 'inline' : 'none';
        }

        updateButtons();
        updateProgress(); // Initial call to set the progress
    </script>
</body>
</html>
