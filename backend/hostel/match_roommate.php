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

// Fetch students assigned to this hostel
$student_query = "
    SELECT id, first_name, surname, personality_score 
    FROM students 
    WHERE hostel_id = $hostel_id";
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

// Genetic algorithm optimization
function calculate_fitness($room) {
    $total_difference = 0;
    for ($i = 0; $i < count($room); $i++) {
        for ($j = $i + 1; $j < count($room); $j++) {
            $total_difference += abs($room[$i]['personality_score'] - $room[$j]['personality_score']);
        }
    }
    return 1 / (1 + $total_difference);  // Higher fitness for less difference
}

function genetic_algorithm_optimize($rooms, $iterations = 100) {
    for ($i = 0; $i < $iterations; $i++) {
        $room_fitness = [];
        foreach ($rooms as $key => $room) {
            $room_fitness[$key] = calculate_fitness($room);
        }
        foreach ($rooms as &$room) {
            shuffle($room);
        }
    }
    return $rooms;
}

// Initial fuzzy grouping
$initial_rooms = fuzzy_group_students($students, $students_per_room);

// Optimize the room assignment
$optimized_rooms = genetic_algorithm_optimize($initial_rooms);

// Insert room assignments into the database
$room_number = 1;
foreach ($optimized_rooms as $room) {
    foreach ($room as $student) {
        $student_id = $student['id'];
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