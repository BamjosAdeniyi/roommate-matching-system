<?php
include '../../config/db_connect.php';

// Get the hostel ID from the query parameter
$hostel_id = isset($_GET['hostel_id']) ? intval($_GET['hostel_id']) : 0;
if ($hostel_id == 0) {
    die("Invalid hostel ID.");
}

// Fetch hostel details (number of rooms, students per room)
$hostel_query = "
    SELECT number_of_rooms, students_per_room 
    FROM hostels 
    WHERE id = $hostel_id";
$hostel_result = mysqli_query($conn, $hostel_query);

if (mysqli_num_rows($hostel_result) == 0) {
    die("Hostel not found.");
}

$hostel = mysqli_fetch_assoc($hostel_result);
$students_per_room = $hostel['students_per_room'];
$number_of_rooms = $hostel['number_of_rooms'];

// Fetch students assigned to this hostel along with their personality traits
$student_query = "
    SELECT s.id, s.first_name, s.surname, 
           pt.agreeableness, pt.conscientiousness, pt.extraversion, 
           pt.neuroticism, pt.openness
    FROM students s
    INNER JOIN personality_traits pt ON s.id = pt.student_id
    WHERE s.hostel_id = $hostel_id";
$student_result = mysqli_query($conn, $student_query);

$students = [];
while ($row = mysqli_fetch_assoc($student_result)) {
    $students[] = $row;
}

// Fuzzy logic for initial pairing
function fuzzy_group_students($students, $students_per_room) {
    $rooms = [];
    shuffle($students);  // Shuffle students to start random pairing
    $current_room = [];

    foreach ($students as $student) {
        // Add student to the current room
        $current_room[] = $student;

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

// Fitness calculation using individual trait differences
function calculate_fitness($room) {
    $total_difference = 0;

    // Iterate through pairs of students and calculate trait differences
    for ($i = 0; $i < count($room); $i++) {
        for ($j = $i + 1; $j < count($room); $j++) {
            // Calculate absolute difference in personality traits
            $agreeableness_diff = abs($room[$i]['agreeableness'] - $room[$j]['agreeableness']);
            $conscientiousness_diff = abs($room[$i]['conscientiousness'] - $room[$j]['conscientiousness']);
            $extraversion_diff = abs($room[$i]['extraversion'] - $room[$j]['extraversion']);
            $neuroticism_diff = abs($room[$i]['neuroticism'] - $room[$j]['neuroticism']);
            $openness_diff = abs($room[$i]['openness'] - $room[$j]['openness']);

            // Sum up the trait differences
            $total_difference += $agreeableness_diff + $conscientiousness_diff + $extraversion_diff +
                                 $neuroticism_diff + $openness_diff;
        }
    }
    return 1 / (1 + $total_difference);  // Higher fitness for less difference
}

// Genetic algorithm optimization
function genetic_algorithm_optimize($rooms, $iterations = 100) {
    // Flatten student list across all rooms
    $all_students = [];
    foreach ($rooms as $room) {
        $all_students = array_merge($all_students, $room);
    }

    // Run optimization iterations
    for ($i = 0; $i < $iterations; $i++) {
        shuffle($all_students);  // Shuffle all students

        // Reassign students to rooms ensuring uniqueness
        $new_rooms = [];
        $student_index = 0;

        foreach ($rooms as $key => $room) {
            $new_rooms[$key] = array_slice($all_students, $student_index, count($room));
            $student_index += count($room);
        }

        // Replace the rooms with the newly optimized ones
        $rooms = $new_rooms;
    }

    return $rooms;
}

// Initial fuzzy grouping
$initial_rooms = fuzzy_group_students($students, $students_per_room);

// Optimize the room assignment using the genetic algorithm
$optimized_rooms = genetic_algorithm_optimize($initial_rooms);

// Clear previous room assignments for this hostel
$delete_query = "DELETE FROM room_assignments WHERE hostel_id = $hostel_id";
mysqli_query($conn, $delete_query);

// Prepare to track assigned students
$assigned_students = [];  // To prevent duplicates

// Insert room assignments into the database
$room_number = 1;
foreach ($optimized_rooms as $room) {
    foreach ($room as $student) {
        $student_id = $student['id'];
        
        // Check if student is already assigned
        if (in_array($student_id, $assigned_students)) {
            echo "Error: Student with ID $student_id is assigned more than once.<br>";
            continue;
        }

        // Track the assigned student
        $assigned_students[] = $student_id;

        // Insert the assignment into the database
        $insert_query = "
            INSERT INTO room_assignments (student_id, hostel_id, room_number)
            VALUES ($student_id, $hostel_id, $room_number)";
        mysqli_query($conn, $insert_query);
    }
    $room_number++;
}

mysqli_close($conn);
echo "Room assignments have been successfully completed!";
?>
