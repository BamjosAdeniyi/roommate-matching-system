<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start time tracking
$start_time = microtime(true);
session_start(); // Start the session
// Check if the session clear button was pressed
if (isset($_POST['clear_session'])) {
    session_unset(); // Clear all session variables
    echo "<h4>Session data cleared!</h4>";
}
include '../../config/db_connect.php';
// Get the scenario ID from the query parameter
$scenario_id = isset($_GET['scenario_id']) ? intval($_GET['scenario_id']) : 0;
if ($scenario_id == 0) {
    die("Invalid scenario ID.");
}
// Fetch scenario details (students) based on the scenario ID
$scenario_query = "
    SELECT id, name, agreeableness, conscientiousness, extraversion, 
           neuroticism, openness, hostel_id
    FROM scenario_$scenario_id";
$scenario_result = mysqli_query($conn, $scenario_query);
if (mysqli_num_rows($scenario_result) == 0) {
    die("No students found for this scenario.");
}
$students = [];
while ($row = mysqli_fetch_assoc($scenario_result)) {
    $students[] = $row;
}
// Fuzzy membership functions
function triangular_membership($x, $a, $b, $c) {
    // Triangular membership function
    if ($x < $a || $x > $c) return 0;
    if ($x == $a) return 1;
    if ($x == $b) return 1;
    if ($x < $b) return ($x - $a) / ($b - $a);
    return ($c - $x) / ($c - $b);
}
// Enhanced fuzzy_group_students function
// Fuzzy logic for initial pairing, considering all traits
function fuzzy_group_students($students, $students_per_room) {
    $rooms = [];
    $current_room = [];
    // Fuzzy logic grouping
    foreach ($students as $student) {
        // Fuzzify traits
        $fuzzy_scores = [
            'agreeableness' => triangular_membership($student['agreeableness'], 0, 60, 120),
            'conscientiousness' => triangular_membership($student['conscientiousness'], 0, 60, 120),
            'extraversion' => triangular_membership($student['extraversion'], 0, 60, 120),
            'neuroticism' => triangular_membership($student['neuroticism'], 0, 40, 80),
            'openness' => triangular_membership($student['openness'], 0, 60, 120),
        ];
        // Add student to the current room based on fuzzy compatibility
        if (can_add_student_to_room($current_room, $student, $fuzzy_scores, $students_per_room)) {
            $current_room[] = $student;
        }
        // Check if the room is full
        if (count($current_room) == $students_per_room) {
            $rooms[] = $current_room;  // Save the room assignment
            $current_room = [];  // Reset for the next room
        }
    }
    // If there are any students left in the last room
    if (!empty($current_room)) {
        $rooms[] = $current_room;
    }
    return $rooms;
}
// Check if we can add this student to the current room based on fuzzy logic
function can_add_student_to_room($current_room, $student, $fuzzy_scores, $students_per_room) {
    // If the room is already full
    if (count($current_room) >= $students_per_room) {
        return false; // Room is full
    }
    // Define weights for the personality traits based on their importance
    $trait_weights = [
        'agreeableness' => 0.3,
        'conscientiousness' => 0.25,
        'extraversion' => 0.15,
        'neuroticism' => 0.1,
        'openness' => 0.2,
    ];
    // Compatibility score for this student with the room
    foreach ($current_room as $room_student) {
        $room_fuzzy_scores = [
            'agreeableness' => triangular_membership($room_student['agreeableness'], 0, 60, 120),
            'conscientiousness' => triangular_membership($room_student['conscientiousness'], 0, 60, 120),
            'extraversion' => triangular_membership($room_student['extraversion'], 0, 60, 120),
            'neuroticism' => triangular_membership($room_student['neuroticism'], 0, 40, 80),
            'openness' => triangular_membership($room_student['openness'], 0, 60, 120),
        ];
        // Calculate the weighted compatibility score based on fuzzy logic and weights
        $compatibility = 0;
        foreach ($fuzzy_scores as $trait => $score) {
            $room_trait_score = $room_fuzzy_scores[$trait];
            // New fuzzy rule: consider the difference in fuzzy scores
            $difference = abs($score - $room_trait_score);
            // Fuzzy rule: Higher difference in neuroticism reduces compatibility more
            if ($trait === 'neuroticism') {
                $compatibility -= $difference * $trait_weights[$trait];
            } else {
                // For other traits, similarity improves compatibility
                $compatibility += (1 - $difference) * $trait_weights[$trait];
            }
        }
        // Check if compatibility is below a dynamic threshold (based on room size)
        $threshold = 0.37 + (0.05 * (count($current_room) / $students_per_room)); // Dynamic threshold
        if ($compatibility < $threshold) {
            return false; // Not compatible enough
        }
    }
    return true; // Compatible enough to add to the room
}
// Fitness calculation using individual trait differences with weights and neuroticism penalty
function calculate_fitness($room) {
    $total_difference = 0;
    $total_neuroticism = 0; // To calculate average neuroticism
    $compatibility_score = 0;
    // Define weights for each personality trait
    $weights = [
        'agreeableness' => 0.3,
        'conscientiousness' => 0.25,
        'extraversion' => 0.15,
        'neuroticism' => 0.1,
        'openness' => 0.2
    ];
    // Iterate through pairs of students and calculate trait differences
    for ($i = 0; $i < count($room); $i++) {
        for ($j = $i + 1; $j < count($room); $j++) {
            // Calculate weighted absolute difference in personality traits
            $agreeableness_diff = abs($room[$i]['agreeableness'] - $room[$j]['agreeableness']) * $weights['agreeableness'];
            $conscientiousness_diff = abs($room[$i]['conscientiousness'] - $room[$j]['conscientiousness']) * $weights['conscientiousness'];
            $extraversion_diff = abs($room[$i]['extraversion'] - $room[$j]['extraversion']) * $weights['extraversion'];
            $neuroticism_diff = abs($room[$i]['neuroticism'] - $room[$j]['neuroticism']) * $weights['neuroticism'];
            $openness_diff = abs($room[$i]['openness'] - $room[$j]['openness']) * $weights['openness'];
            // Sum up the trait differences
            $total_difference += $agreeableness_diff + $conscientiousness_diff + $extraversion_diff +
                                 $neuroticism_diff + $openness_diff;
            // Compatibility logic (e.g., favor pairs with lower differences)
            $compatibility_score += 1 / (1e-10 + $agreeableness_diff + $conscientiousness_diff); // Higher for lower differences
        }
    }
    // Calculate total neuroticism for penalty
    foreach ($room as $student) {
        $total_neuroticism += $student['neuroticism'];
    }
    
    // Calculate average neuroticism
    $average_neuroticism = $total_neuroticism / count($room);
    // Apply penalty for high neuroticism
    $neuroticism_penalty = max(0, $average_neuroticism - 65) * 0.1;
    // Calculate fitness score
    $compatibility_score = max(0, min($compatibility_score, 1));
    $total_difference = max(0, min($total_difference, 1));
    $neuroticism_penalty = max(0, min($neuroticism_penalty, 1));
    $fitness = (1 / (1e-10 + $total_difference + $neuroticism_penalty)) * $compatibility_score;  // Higher fitness for less difference
    return $fitness; 
}
// Genetic algorithm optimization with elitism
function genetic_algorithm_optimize($rooms, $iterations = 400, $mutation_rate = 0.1, $elitism_rate = 0.3) {
    $all_students = [];
    foreach ($rooms as $room) {
        $all_students = array_merge($all_students, $room);
    }
    // Determine the number of elitism candidates to retain
    $elitism_count = max(1, intval(count($rooms) * $elitism_rate));
    // Store the best rooms for elitism
    $best_rooms = [];
    for ($i = 0; $i < $iterations; $i++) {
        shuffle($all_students); // Shuffle students
        $new_rooms = [];
        $student_index = 0;
        foreach ($rooms as $key => $room) {
            $new_rooms[$key] = array_slice($all_students, $student_index, count($room));
            $student_index += count($room);
        }
        // Mutation: Randomly swap two students between rooms
        if (rand(0, 100) / 100 < $mutation_rate) {
            $room_a = rand(0, count($new_rooms) - 1);
            $room_b = rand(0, count($new_rooms) - 1);
            // Ensure the rooms are different
            if ($room_a !== $room_b) {
                $student_a_index = rand(0, count($new_rooms[$room_a]) - 1);
                $student_b_index = rand(0, count($new_rooms[$room_b]) - 1);
                // Swap the students
                $temp = $new_rooms[$room_a][$student_a_index];
                $new_rooms[$room_a][$student_a_index] = $new_rooms[$room_b][$student_b_index];
                $new_rooms[$room_b][$student_b_index] = $temp;
            }
        }
        // Calculate fitness for new rooms
        $fitness_scores = [];
        foreach ($new_rooms as $room) {
            $fitness_scores[] = calculate_fitness($room);
        }
        // Identify the best rooms for elitism
        arsort($fitness_scores);
        $best_keys = array_keys(array_slice($fitness_scores, 0, $elitism_count, true));
        // Reset the best rooms for this iteration
        if ($i === 0) {
            // Initialize best_rooms with the first set of best configurations
            foreach ($best_keys as $key) {
                $best_rooms[$key] = $new_rooms[$key];
            }
        } else {
            // Merge with existing best rooms while ensuring uniqueness
            foreach ($best_keys as $key) {
                $best_rooms[$key] = $new_rooms[$key];
            }
        }
        // Update rooms with new assignments
        $rooms = $new_rooms;
    }
    // // Limit the output to the top 5 best rooms based on fitness
    // $final_best_rooms = array_slice($best_rooms, 0, 10);
    // return $final_best_rooms;
       return $best_rooms;
}
// Fetch hostel details (number of rooms, students per room)
$hostel_query = "
    SELECT students_per_room 
    FROM hostels 
    WHERE id = (SELECT hostel_id FROM scenario_$scenario_id LIMIT 1)";
$hostel_result = mysqli_query($conn, $hostel_query);
if (mysqli_num_rows($hostel_result) == 0) {
    die("Hostel not found.");
}
$hostel = mysqli_fetch_assoc($hostel_result);
$students_per_room = $hostel['students_per_room'];
// Initial fuzzy grouping
$initial_rooms = fuzzy_group_students($students, $students_per_room);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// echo "INITIAL ROOMS";
// echo "Total students in initial rooms: " . array_reduce($initial_rooms, function($carry, $room) {
//     return $carry + count($room);
// }, 0) . "\n";
// echo "<pre>"; // Helps to format the output for better readability
// print_r($initial_rooms); // or var_dump($initialRooms);
// echo "</pre>";
////////////////////////////////////////////////////////////////////////////////////////////////////////
// Optimize the room assignment using the genetic algorithm with elitism
$optimized_rooms = genetic_algorithm_optimize($initial_rooms);
///////////////////////////////////////////////////////////////////////////////////////////////////////
// echo "OPTIMIZED ROOMS";
// echo "Total students in optimized rooms: " . array_reduce($optimized_rooms, function($carry, $room) {
//     return $carry + count($room);
// }, 0) . "\n";
// echo "<pre>"; // Helps to format the output for better readability
// print_r($optimized_rooms); // or var_dump($initialRooms);
// echo "</pre>";
////////////////////////////////////////////////////////////////////////////////////////////////////////
// Clear previous room assignments for this hostel
$delete_query = "DELETE FROM room_assignments WHERE hostel_id = (SELECT hostel_id FROM scenario_$scenario_id LIMIT 1)";
mysqli_query($conn, $delete_query);
// Prepare to track assigned students
$assigned_students = [];  // To prevent duplicates
// Insert room assignments into the database and display results
$room_number = 1;
echo "<h3>Room Assignments for Scenario ID $scenario_id</h3>";
foreach ($optimized_rooms as $room) {
    echo "<h4>Room $room_number:</h4>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr><th>Student Name</th><th>Agreeableness</th><th>Conscientiousness</th><th>Extraversion</th><th>Neuroticism</th><th>Openness</th></tr>";
    foreach ($room as $student) {
        // Track the assigned student
        $student_name = $student['name'];
        $student_id = $student['id']; // This ID should now correspond to the ID in the scenario table
        // Check if the student is already assigned to a room
        $check_query = "SELECT COUNT(*) as count FROM room_assignments WHERE student_id = $student_id";
        $check_result = mysqli_query($conn, $check_query);
        $check_row = mysqli_fetch_assoc($check_result);
        if ($check_row['count'] == 0) { // Only insert if the student is not already assigned
            // Insert the assignment into the database
            $insert_query = "
                INSERT INTO room_assignments (student_id, hostel_id, room_number)
                VALUES ($student_id, (SELECT hostel_id FROM scenario_$scenario_id LIMIT 1), $room_number)";
            mysqli_query($conn, $insert_query);
        }
        // Display the student details
        echo "<tr>
                <td>$student_name</td>
                <td>{$student['agreeableness']}</td>
                <td>{$student['conscientiousness']}</td>
                <td>{$student['extraversion']}</td>
                <td>{$student['neuroticism']}</td>
                <td>{$student['openness']}</td>
              </tr>";
    }
    echo "</table>";
    $room_number++;
}
// Calculate average fitness score
$total_fitness = 0;
foreach ($optimized_rooms as $room) {
    $total_fitness += calculate_fitness($room);
}
$average_fitness = $total_fitness / count($optimized_rooms);
// Store the average fitness score in the session
if (!isset($_SESSION['fitness_scores'])) {
    $_SESSION['fitness_scores'] = [];
}
$_SESSION['fitness_scores'][] = $average_fitness;
// Calculate stability metrics
$fitness_count = count($_SESSION['fitness_scores']);
if ($fitness_count > 1) {
    $mean = array_sum($_SESSION['fitness_scores']) / $fitness_count;
    $variance = array_reduce($_SESSION['fitness_scores'], function($carry, $score) use ($mean) {
        return $carry + pow($score - $mean, 2);
    }, 0) / ($fitness_count - 1);
    $std_dev = sqrt($variance);
    
    echo "<h4>Current Average Fitness Score: $average_fitness</h4>";
    echo "<h4>Mean average Fitness Score: $mean</h4>";
    echo "<h4>Fitness Score Stability (Standard Deviation): $std_dev</h4>";
} else {
    echo "<h4>Current Average Fitness Score: $average_fitness</h4>";
}
// Optional: Display all recorded fitness scores
echo "<h4>Recorded Fitness Scores: " . implode(", ", $_SESSION['fitness_scores']) . "</h4>";
mysqli_close($conn);
echo "Room assignments have been successfully completed!";
// At the end of the script
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
// Display execution time
echo "<h4>Execution Time: " . number_format($execution_time, 4) . " seconds</h4>";
?>
<form action="test_algo_backend.php" method="POST">
    <button type="submit" name="clear_session">Clear Session Data</button>
</form>