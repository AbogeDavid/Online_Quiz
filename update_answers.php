<?php
include_once 'database.php';

if (isset($_POST['save']) && isset($_POST['qid'])) {
    $qid = $_POST['qid'];

    // Iterate through the submitted data to update answers
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'option') === 0) {
            // Extract the option ID from the field name
            $optionid = substr($key, 6);

            // Update the answer in the database
            $updatedOption = mysqli_real_escape_string($con, $value);
            $updateQuery = "UPDATE `options` SET `option` = '$updatedOption' WHERE `qid` = '$qid' AND `optionid` = '$optionid';";
            mysqli_query($con, $updateQuery) or die('Error updating option: ' . mysqli_error($con));
        }
    }

    // Display a popup message using JavaScript
    echo '<script>
    alert("Answers updated successfully!");
    window.location.href = "dashboard.php?q=6";
    </script>';
    exit();
} else {
    echo "Invalid request.";
}
?>
