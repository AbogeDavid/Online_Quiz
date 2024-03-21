<?php
    include_once 'database.php';
    session_start();
    if(!(isset($_SESSION['email'])))
    {
        header("location:login.php");
    }
    else
    {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        include_once 'database.php';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome | Online Quiz System</title>
    <link  rel="stylesheet" href="css/bootstrap.min.css"/>
    <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
    <link rel="stylesheet" href="css/welcome.css">
    <link  rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"  type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body>
    <nav class="navbar navbar-default title1">
        <div class="container-fluid">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        <a class="navbar-brand" href="#"><b>Online Quiz System</b></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-left">
        
            <li <?php if(@$_GET['q']==1) echo'class="active"'; ?> ><a href="welcome.php?q=5"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>&nbsp;Welcome<span class="sr-only">(current)</span></a></li>
            <li <?php if(@$_GET['q']==5) echo'class="active"'; ?> ><a href="welcome.php?q=5"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span></a></li>
            <li <?php if(@$_GET['q']==2) echo'class="active"'; ?>> <a href="welcome.php?q=2"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History</a></li>
            <li <?php if(@$_GET['q']==3) echo'class="active"'; ?>> <a href="welcome.php?q=3"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li <?php echo''; ?> > <a href="logout.php?q=welcome.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Log out</a></li>
        </ul>
        </div>
        </div>
        </nav>
        <br><br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <?php
                if (isset($_POST['showanswer'])) {
                    $q = mysqli_query($con, "SELECT * FROM `history` WHERE wrong > 0 AND sahi < `level` ORDER BY date DESC") or die('Error');
                    $q1 = mysqli_query($con, "SELECT * FROM answer JOIN questions ON answer.qid = questions.qid;");

                    echo '<div class="panel title">
                            <table class="table table-striped title1" >
                                <tr style="color:black;">
                                    <td><center><b>Question</b></center></td>
                                    <td><center><b>Correct Answer</b></center></td>
                                </tr>';

                    while ($row = mysqli_fetch_array($q)) {
                        $eid = $row['eid'];
                        $s = $row['score'];
                        $w = $row['wrong'];
                        $q23 = mysqli_query($con, "SELECT qid, qns FROM questions WHERE eid='$eid' AND qid IN (SELECT qid FROM history WHERE wrong > 0) ") or die('Error208');

                        while ($rowQ = mysqli_fetch_array($q23)) {
                            $qid = $rowQ['qid'];
                            $qns = $rowQ['qns'];

                            // Display question row
                            echo '<tr>
                                    <td><center>' . $qns . '</center></td>';
                            // Display corresponding answers
                            $q2 = mysqli_query($con, "SELECT answer.ansid, answer.qid, questions.qns, options.optionid, options.option 
                                                    FROM answer 
                                                    JOIN options ON options.optionid = answer.ansid 
                                                    JOIN questions ON answer.qid = questions.qid 
                                                    WHERE answer.qid = '$qid'") or die('Error208');

                            while ($row1 = mysqli_fetch_array($q2)) {
                                $optionid = $row1['optionid'];
                                $options = $row1['option'];
                                echo '<td><center>' . $options . '</center></td>';
                            }

                            echo '</tr>';
                        }
                    }

                    echo '</table></div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
