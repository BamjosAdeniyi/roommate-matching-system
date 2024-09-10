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

// Fitness calculation using individual trait differences with weights and neuroticism penalty
function calculate_fitness($room) {
  $total_difference = 0;
  $total_neuroticism = 0; // To calculate average neuroticism

  // Define weights for each personality trait
  $weights = [
      'agreeableness' => 1,
      'conscientiousness' => 1,
      'extraversion' => 1,
      'neuroticism' => 1, // You can adjust this weight as needed
      'openness' => 1
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
      }
  }

  // Calculate total neuroticism for penalty
  foreach ($room as $student) {
      $total_neuroticism += $student['neuroticism'];
  }
  
  // Calculate average neuroticism
  $average_neuroticism = $total_neuroticism / count($room);

  // Apply penalty for high neuroticism (you can adjust the penalty factor)
  $neuroticism_penalty = max(0, $average_neuroticism - 60) * 0.1; // Penalizes neuroticism above a threshold (70)

  // Calculate fitness score
  $fitness = 1 / (1 + $total_difference + $neuroticism_penalty);  // Higher fitness for less difference

  return $fitness; 
}



// Genetic algorithm optimization
function genetic_algorithm_optimize($rooms, $iterations = 400, $mutation_rate = 0.10) {
  $all_students = [];
  foreach ($rooms as $room) {
      $all_students = array_merge($all_students, $room);
  }

  for ($i = 0; $i < $iterations; $i++) {
      shuffle($all_students);  // Shuffle students

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

          $student_a_index = rand(0, count($new_rooms[$room_a]) - 1);
          $student_b_index = rand(0, count($new_rooms[$room_b]) - 1);

          // Swap the students
          $temp = $new_rooms[$room_a][$student_a_index];
          $new_rooms[$room_a][$student_a_index] = $new_rooms[$room_b][$student_b_index];
          $new_rooms[$room_b][$student_b_index] = $temp;
      }

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

// Insert room assignments into the database and display results
$room_number = 1;

echo "<h3>Room Assignments for Hostel ID $hostel_id</h3>";
foreach ($optimized_rooms as $room) {
    echo "<h4>Room $room_number:</h4>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr><th>Student Name</th><th>Agreeableness</th><th>Conscientiousness</th><th>Extraversion</th><th>Neuroticism</th><th>Openness</th></tr>";

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

        // Display the student details
        echo "<tr>
                <td>{$student['first_name']} {$student['surname']}</td>
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

mysqli_close($conn);
echo "Room assignments have been successfully completed!";
?>