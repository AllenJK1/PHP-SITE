<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['vote'])) {
    if (count($_POST) == 1) {
        $_SESSION['error'][] = 'Please vote at least one candidate';
    } else {
        $_SESSION['post'] = $_POST;
        $sql = "SELECT * FROM positions";
        $query = $conn->query($sql);
        $error = false;
        $sql_array = array();

        // Path to the vote IDs file in the same directory
        $filePath = __DIR__ . '/voteids.txt'; // __DIR__ is the directory of the current PHP file
        
        // Check if the file exists and is not empty
        if (file_exists($filePath) && filesize($filePath) > 0) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $vote_id = array_shift($lines); // Take the first vote ID and remove it from the array
            file_put_contents($filePath, implode("\n", $lines)); // Save the remaining IDs back to the file
        } else {
            $_SESSION['error'][] = 'No more vote IDs available or file does not exist.';
            $error = true;
            header('location: home.php');
            exit(); // Ensure the script stops executing if there's no vote ID
        }

        // Proceed if a vote ID was successfully obtained
        if (!$error) {
            while ($row = $query->fetch_assoc()) {
                $position = slugify($row['description']);
                $pos_id = $row['id'];
                if (isset($_POST[$position])) {
                    $candidates = $_POST[$position];
                    $candidates = is_array($candidates) ? $candidates : array($candidates);
                    
                    if (count($candidates) > $row['max_vote']) {
                        $error = true;
                        $_SESSION['error'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                    } else {
                        foreach ($candidates as $candidate_id) {
                            if (!empty($candidate_id) && is_numeric($candidate_id)) {
                                $sql_array[] = "INSERT INTO votes (vote_id, candidate_id, position_id) VALUES ('$vote_id', '$candidate_id', '$pos_id')";
                            } else {
                                $_SESSION['error'][] = 'Invalid candidate ID for position: ' . $row['description'];
                                $error = true;
                                break;
                            }
                        }
                    }
                }
            }

            if (!$error) {
                foreach ($sql_array as $sql_row) {
                    if (!$conn->query($sql_row)) {
                        $_SESSION['error'][] = 'Database error: ' . $conn->error;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    $updateVotedSql = "UPDATE voters SET voted = 1 WHERE id = '".$voter['id']."'";
                    if ($conn->query($updateVotedSql)) {
                        $_SESSION['success'] = 'Ballot Submitted. Your vote id is ' . $vote_id;
                    } else {
                        $_SESSION['error'][] = 'An error occurred while updating your voting status: ' . $conn->error;
                    }
                }
            }
        }
        unset($_SESSION['post']);
    }
} else {
    $_SESSION['error'][] = 'Select candidates to vote first';
}

header('location: home.php');
?>
