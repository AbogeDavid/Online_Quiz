<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles1.css">
    <title>Your Page Title</title>
</head>
<body>
    <div class="container">
<?php
include_once 'database.php';

function displayQuizForm($eid, $quizResult, $sn) {
    echo '<form method="post" action="update_questions.php">
            <input type="hidden" name="eid" value="' . $eid . '">'; // Include the quiz ID as a hidden field

    // Iterate through the result set
    while ($quizRow = mysqli_fetch_array($quizResult)) {
        // Display the editable fields for each question
        echo '<div class="panel title">
                <label for="qns' . $quizRow['sn'] . '"><h2>Question: '.$quizRow['sn'].'</h2></label>
                <textarea name="qns' . $quizRow['sn'] . '" id="qns' . $quizRow['sn'] . '">' . $quizRow['qns'] . '</textarea>
                
              </div>';
    }

    // Add a save button
    echo '<input type="submit" name="save" value="Save">
          </form>';
}

function displayOptionsForm($qid, $ansResult) {
    echo '<form method="post" action="update_answers.php">
            <input type="hidden" name="qid" value="' . $qid . '">'; // Include the question ID as a hidden field

    // Iterate through the result set
    while ($optionRow = mysqli_fetch_array($ansResult)) {
        // Display the editable fields for each option
        echo '<div class="panel title">
                <textarea name="option' . $optionRow['optionid'] . '" id="option' . $optionRow['optionid'] . '">' . $optionRow['option'] . '</textarea>
              </div>';
    }

    // Add a save button
    echo '<input type="submit" name="save" value="Save">
          </form>';
}

if (isset($_GET['eid'])) {
    $eid = $_GET['eid'];

    // Fetch questions based on the quiz ID
    $quizResult = mysqli_query($con, "SELECT * FROM `questions` WHERE `eid` = '$eid';") or die('Error fetching quiz details');
    
    // Check if any rows were returned
    if (mysqli_num_rows($quizResult) > 0) {
        // Display the form
        displayQuizForm($eid, $quizResult, $sn);
    } else {
        echo "No questions found for the given quiz ID.";
    }
}

if (isset($_GET['qid'])) {
    $qid = $_GET['qid'];
    echo "<h3> OPTIONS</h3>";

    // Fetch options based on the question ID
    $ansResult = mysqli_query($con, "SELECT * FROM `options` WHERE `qid` = '$qid';") or die('Error fetching options');

    // Check if any rows were returned
    if (mysqli_num_rows($ansResult) > 0) {
        // Display the form
        displayOptionsForm($qid, $ansResult);
    } else {
        echo "No options found for the given question ID.";
    }
}
?>
   </div>
</body>
</html>