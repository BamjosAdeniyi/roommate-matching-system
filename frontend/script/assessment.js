const questions = []; // Initialize an empty array to hold questions

// Function to initialize the assessment with the provided questions
function initializeAssessment(questionsData) {
    questions.push(...questionsData); // Populate the questions array
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const questionContainers = document.querySelectorAll('.question-container');
    const progressDisplay = document.getElementById('progress');
    const alertMessage = document.getElementById('alertMessage');
    let currentIndex = 0;

    function updateProgress() {
        progressDisplay.textContent = `Question ${currentIndex + 1} of ${questionContainers.length}`; // Update progress text
    }

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            questionContainers[currentIndex].classList.remove('active');
            currentIndex--;
            questionContainers[currentIndex].classList.add('active');
            alertMessage.style.display = 'none'; // Hide alert message when navigating back
        }
        updateButtons();
        updateProgress(); // Update progress when navigating
    });

    nextBtn.addEventListener('click', () => {
        // Check if the current question has been answered
        const selectedOption = document.querySelector(`input[name="responses[${questions[currentIndex].id}]"]:checked`);
        if (!selectedOption) {
            alertMessage.style.display = 'block'; // Show alert message
            return; // Do not proceed if no option is selected
        } else {
            alertMessage.style.display = 'none'; // Hide alert message if an option is selected
        }

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
}

// Call this function with the questions data
document.addEventListener('DOMContentLoaded', () => {
    const questionsDataElement = document.getElementById('questionsData');
    const questionsData = JSON.parse(questionsDataElement.textContent);
    console.log('Questions data:', questionsData); // Debug log
    initializeAssessment(questionsData);
});
