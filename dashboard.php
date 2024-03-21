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
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Online Questions</title>
    <link  rel="stylesheet" href="css/bootstrap.min.css"/>
    <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
    <link rel="stylesheet" href="css/welcome.css">
    <link  rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"  type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a class="navbar-brand" href="Javascript:void(0)"><b>Online Quiz System</b></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li <?php if(@$_GET['q']==1) echo'class="active"'; ?>><a href="dashboard.php?q=1">USER</a></li>
                    
                    <li class="dropdown <?php if(@$_GET['q']==4 || @$_GET['q']==5) echo'active"'; ?>">
                    <li><a href="dashboard.php?q=4">ADD</a></li>
                    <li><a href="dashboard.php?q=5">DELETE</a></li>
                    <li <?php if(@$_GET['q']==6) echo'class="active"'; ?> ><a href="dashboard.php?q=6">&nbsp;EDIT</a></li> 
                    <li <?php if(@$_GET['q']==2) echo'class="active"'; ?>><a href="dashboard.php?q=2">REPORT<span class="sr-only"></span></a></li>      
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo''; ?> > <a href="logout1.php?q=dashboard.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <?php
          if (@$_GET['q'] == 2) {

            // Function to display the Rank Table
            function displayRankTable($con)
            {
                $q = mysqli_query($con, "SELECT R.email, R.score, H.`level`
                    FROM `rank` R
                    JOIN history H ON R.email = H.email
                    WHERE H.`level` IS NOT NULL
                    ORDER BY R.score DESC ") or die('Error223');
            
                echo '<div class="panel title"><div class="table-responsive">
                        <table class="table table-striped title1" >
                            <tr style="color:red">
                                <td><center><b>Rank</b></center></td>
                                <td><center><b>Email</b></center></td>
                                <td><center><b>Scores</b></center></td>
                            </tr>';
            
                $c = 0;
                $totalAttendees = 0;
                $totalScores = 0;
                $passedCount = 0;
                $failedCount = 0;
            
                while ($row = mysqli_fetch_array($q)) {
                    $e = $row['email'];
                    $s = $row['score'];
                    $l = $row['level'];
            
                    // Determine passed or failed
                    if ($s == $l) {
                        $passedCount++;
                    } else {
                        $failedCount++;
                    }
            
                    $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e' ") or die('Error231');
            
                    while ($row = mysqli_fetch_array($q12)) {
                        $name = $row['name'];
                        $college = $row['college'];
                    }
            
                    $c++;
                    $totalAttendees++;
                    $totalScores += $s;
            
                    echo '<tr>
                            <td style="color:#99cc32"><center><b>' . $c . '</b></center></td>
                            <td><center>' . $e . '</center></td>
                            <td><center>' . $s . '</center></td>';
                }
            
                // Add download and print buttons
                echo '<div class="btn myPDbuttons">
                        <button onclick="downloadData()">Download</button>
                        <button onclick="printData()">Print</button>
                      </div>';
            
                // Calculate average score
                $averageScore = ($totalAttendees > 0) ? ($totalScores / $totalAttendees) : 0;
            
                echo '<tr>
                        <td colspan="2"><center><b>Total Attendees: ' . $totalAttendees . '</b></center></td>
                        <td><center><b>Average Score: ' . number_format($averageScore, 2) . '</b></center></td>
                      </tr>';
            
                echo '</table></div></div>';
            
            
                // JavaScript for histogram, download, and print
                echo'<canvas id="myHistogram" width="400" height="200"></canvas>';
                echo '<script>
                        var ctx = document.getElementById("myHistogram").getContext("2d");
                        var myChart = new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: ["Passed", "Failed"],
                                datasets: [{
                                    label: "Passed and Failed Rates",
                                    data: [' . $passedCount . ', ' . $failedCount . '],
                                    backgroundColor: ["green", "red"],
                                }],
                            },
                        });
            
                        function downloadData() {
                            // Add logic to download data (e.g., using AJAX to fetch data and create a downloadable file)
                            alert("Downloading data...");
                        }
            
                        function printData() {
                            // Add logic to print data (e.g., opening a print-friendly page)
                            alert("Printing data...");
                        }
                      </script>';
            }
            
            // Function to display the Total Attendees per Country Table and Pie Chart
            function displayCountryTable($con)
            {
                $q_countries = mysqli_query($con, "SELECT COUNT(email) AS total, country FROM user GROUP BY country") or die('Error in fetching total attendees per country');
            
                echo '<div class="panel title">
                        <h3>Total Attendees per Country</h3>
                        <div class="table-responsive">
                          <table class="table table-striped title1">
                            <tr style="color:red">
                              <td><center><b>Country</b></center></td>
                              <td><center><b>Total Attendees</b></center></td>
                            </tr>';
            
                while ($row_country = mysqli_fetch_array($q_countries)) {
                    $country = $row_country['country'];
                    $total_attendees_country = $row_country['total'];
            
                    echo '<tr>
                            <td><center>' . $country . '</center></td>
                            <td><center>' . $total_attendees_country . '</center></td>
                          </tr>';
                }
            
                // JavaScript for Total Attendees per Country Pie Chart
                echo '<script>
                        var ctxCountryPie = document.getElementById("countryPieChart").getContext("2d");
                        var countryLabels = ["Kemya", "Uganda", "Tanzania","Albama","Nigeria" ]; // Replace with your actual labels
                        var countryTotals = [12, 6, 4, 1, 5]; // Replace with your actual totals
            
                        var countryPieChart = new Chart(ctxCountryPie, {
                            type: "pie",
                            data: {
                                labels: countryLabels,
                                datasets: [{
                                    data: countryTotals,
                                    backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                                }],
                            },
                        });
                      </script>';
            
                echo '<canvas id="countryPieChart" width="400" height="200"></canvas>';
            
                echo '</table></div></div>';
            }

function displayTotalAttendeesPerCompany($con)
{
    $q_companies = mysqli_query($con, "SELECT COUNT(email) AS total, college FROM user GROUP BY college") or die('Error in fetching total attendees per company');

    echo '<div class="panel title">
            <h3>Total Attendees per Company</h3>
            <div class="table-responsive">
              <table class="table table-striped title1">
                <tr style="color:red">
                  <td><center><b>Company</b></center></td>
                  <td><center><b>Total Attendees</b></center></td>
                </tr>';

    while ($row_company = mysqli_fetch_array($q_companies)) {
        $company = $row_company['college'];
        $total_attendees_company = $row_company['total'];

        echo '<tr>
                <td><center>' . $company . '</center></td>
                <td><center>' . $total_attendees_company . '</center></td>
              </tr>';
    }

    echo '</table></div></div>';

    // JavaScript for Line Graph
    echo '<canvas id="companyLineGraph" width="400" height="200"></canvas>';
    echo '<script>
            var companyLabels = ["KBC", "AQUA", "KEBS", "KEMRI", "KPLC"]; 
            var companyTotals = [1, 1, 1, 1, 2]; 

            var ctxLineGraph = document.getElementById("companyLineGraph").getContext("2d");
            var companyLineGraph = new Chart(ctxLineGraph, {
                type: "line",
                data: {
                    labels: companyLabels,
                    datasets: [{
                        label: "Total Attendees",
                        data: companyTotals,
                        borderColor: "blue",
                        borderWidth: 2,
                        fill: false,
                    }],
                },
            });
          </script>';

}

function displayHighestScoresPerCountryAndCompany($con)
{
    $q_highest_scores = mysqli_query($con, "SELECT MAX(score) AS max_score, email FROM rank GROUP BY email") or die('Error in fetching highest scores');

    echo '<div class="panel title">
            <h3>Highest Scores/Passed per Country and Company</h3>
            <div class="table-responsive">
              <table class="table table-striped title1">
                <tr style="color:red">
                  <td><center><b>Country</b></center></td>
                  <td><center><b>Company</b></center></td>
                  <td><center><b>Email</b></center></td>
                  <td><center><b>Highest Score/Passed</b></center></td>
                </tr>';

    while ($row_highest_score = mysqli_fetch_array($q_highest_scores)) {
        $email = $row_highest_score['email'];
        $max_score = $row_highest_score['max_score'];

        $q_user_details = mysqli_query($con, "SELECT country, college FROM user WHERE email='$email'") or die('Error in fetching user details');

        while ($row_user_details = mysqli_fetch_array($q_user_details)) {
            $country = $row_user_details['country'];
            $company = $row_user_details['college'];
        }

        echo '<tr>
                <td><center>' . $country . '</center></td>
                <td><center>' . $company . '</center></td>
                <td><center>' . $email . '</center></td>
                <td><center>' . $max_score . '</center></td>
              </tr>';
    }

    echo '</table></div></div>';

    // JavaScript for Pie Chart
    echo '<script>
            var countryLabels = ["Kenya", "Tanzania", "Nigeria","Albama","Uganda"]; // Replace with your actual country names
            var countryTotals = [15, 9, 1, 1, 1]; // Replace with your actual highest scores data

            var ctxPieChart = document.getElementById("countryPieChart").getContext("2d");
            var countryPieChart = new Chart(ctxPieChart, {
                type: "pie",
                data: {
                    labels: countryLabels,
                    datasets: [{
                        data: countryTotals,
                        backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#000000","#008000"],
                    }],
                },
            });
          </script>';

    echo '<canvas id="countryPieChart" width="300" height="150"></canvas>';
}

// Call the functions to display data

            displayRankTable($con);
            displayCountryTable($con);
            displayTotalAttendeesPerCompany($con);
            displayHighestScoresPerCountryAndCompany($con);
            

          }
          ?>
        
                <?php 
                    if(@$_GET['q']==1) 
                    {
                        $result = mysqli_query($con,"SELECT * FROM user") or die('Error');
                        echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
                        <tr><td><center><b>S.N.</b></center></td><td><center><b>Name</b></center></td><td><center><b>Organization</b></center></td><td><center><b>Email</b></center></td><td><center><b>Action</b></center></td></tr>';
                        $c=1;
                        while($row = mysqli_fetch_array($result)) 
                        {
                            $name = $row['name'];
                            $email = $row['email'];
                            $college = $row['college'];
                            echo '<tr><td><center>'.$c++.'</center></td><td><center>'.$name.'</center></td><td><center>'.$college.'</center></td><td><center>'.$email.'</center></td><td><center><a title="Delete User" href="update.php?demail='.$email.'"><b><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></b></a></center></td></tr>';
                        }
                        $c=0;
                        echo '</table></div></div>';
                    }
                ?>

                <?php
                    if(@$_GET['q']==4 && !(@$_GET['step']) ) 
                    {
                        
                        echo '<div class="row"><span class="title1" style="margin-left:40%;font-size:30px;color:#fff;"><b>Enter Quiz Details</b></span><br /><br />
                        <div class="col-md-3"></div><div class="col-md-6">   
                        <form class="form-horizontal title1" name="form" action="update.php?q=addquiz"  method="POST">
                            <fieldset>
                                <div class="form-group">
                                <label class="col-md-12 control-label" for="type"></label>  
                                <div class="col-md-12">
                                    <select id="type" name="type" class="form-control input-md">
                                        <option value="1">Tech Trivia</option>
                                        <option value="2">Digital Scavenger Hunt</option>
                                    </select>
                                </div>
                        
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="name"></label>  
                                    <div class="col-md-12">
                                        <input id="name" name="name" placeholder="Enter Quiz title" class="form-control input-md" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="total"></label>  
                                    <div class="col-md-12">
                                        <input id="total" name="total" placeholder="Enter total number of questions" class="form-control input-md" type="number">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="right"></label>  
                                    <div class="col-md-12">
                                        <input id="right" name="right" placeholder="Enter marks on right answer" class="form-control input-md" min="0" type="number">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12 control-label" for="wrong"></label>  
                                    <div class="col-md-12">
                                        <input id="wrong" name="wrong" placeholder="Enter minus marks on wrong answer without sign" class="form-control input-md" min="0" type="number">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for=""></label>
                                    <div class="col-md-12"> 
                                        <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
                                    </div>
                                </div>

                            </fieldset>
                        </form></div>';
                    }
                   
                ?>
        
                <?php
                    if(@$_GET['q']==4 && (@$_GET['step'])==2 ) 
                    {
                        // echo 'Value of $_GET[\'type\']: ' . @$_GET['type'] . '<br>';
                        echo ' 
                        <div class="row">
                        <span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Question Details</b></span><br /><br />
                        <div class="col-md-3"></div><div class="col-md-6"><form class="form-horizontal title1" name="form" action="update.php?q=addqns&n='.@$_GET['n'].'&type='.@$_GET['type'].'&eid='.@$_GET['eid'].'&ch=4 "  method="POST">
                        <fieldset>
                        ';

                        if (@$_GET['type'] == 2){
                            
                            
                            for($i=1;$i<=@$_GET['n'];$i++)
                        {
                            echo '<b>Question number&nbsp;'.$i.'&nbsp;:</><br /><!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="qns'.$i.' "></label>  
                                        <div class="col-md-12">
                                            <textarea rows="3" cols="5" name="qns'.$i.'" class="form-control" placeholder="Write question number '.$i.' here..."></textarea>  
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="'.$i.'1"></label>  
                                        <div class="col-md-12">
                                            <input id="'.$i.'1" name="'.$i.'1" placeholder="Enter option a" class="form-control input-md" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="'.$i.'2"></label>  
                                        <div class="col-md-12">
                                            <input id="'.$i.'2" name="'.$i.'2" placeholder="Enter option b" class="form-control input-md" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="'.$i.'3"></label>  
                                        <div class="col-md-12">
                                            <input id="'.$i.'3" name="'.$i.'3" placeholder="Enter option c" class="form-control input-md" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="'.$i.'4"></label>  
                                        <div class="col-md-12">
                                            <input id="'.$i.'4" name="'.$i.'4" placeholder="Enter option d" class="form-control input-md" type="text">
                                        </div>
                                    </div>
                                    <br />
                                    <b>Correct answer</b>:<br />
                                    <select id="ans'.$i.'" name="ans'.$i.'" placeholder="Choose correct answer " class="form-control input-md" >
                                    <option value="a">Select answer for question '.$i.'</option>
                                    <option value="a"> option a</option>
                                    <option value="b"> option b</option>
                                    <option value="c"> option c</option>
                                    <option value="d"> option d</option> </select><br /><br />'; 
                        }
                        echo '<div class="form-group">
                                <label class="col-md-12 control-label" for=""></label>
                                <div class="col-md-12"> 
                                    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
                                </div>
                              </div>

                        </fieldset>
                        </form></div>';

                        }else{
                            for($i=1;$i<=@$_GET['n'];$i++)
                        {
                            echo '<b>Question number&nbsp;'.$i.'&nbsp;:</><br /><!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="qns'.$i.' "></label>  
                                        <div class="col-md-12">
                                            <textarea rows="3" cols="5" name="qns'.$i.'" class="form-control" placeholder="Write question number '.$i.' here..."></textarea>  
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="'.$i.'1"></label>  
                                        <div class="col-md-12">
                                            <input id="'.$i.'1" name="'.$i.'1" placeholder="Enter option" class="form-control input-md" type="text">
                                        </div>
                                    </div>
                                    <br />
                                    <b>Correct answer</b>:<br />
                                    <select id="ans'.$i.'" name="ans'.$i.'" placeholder="Choose correct answer " class="form-control input-md" >
                                    <option value="a">Select answer for question '.$i.'</option>
                                    <option value="a"> option a</option></select><br /><br />'; 
                        }
                        echo '<div class="form-group">
                                <label class="col-md-12 control-label" for=""></label>
                                <div class="col-md-12"> 
                                    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
                                </div>
                              </div>

                        </fieldset>
                        </form></div>';
                        }
                
                        
                    }
                ?>

                <?php 
                    if(@$_GET['q']==5) 
                    {
                        $result = mysqli_query($con,"SELECT * FROM quiz WHERE type = 2 ORDER BY date DESC") or die('Error');
                        echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
                        <tr><td><center><b>S.N.</b></center></td><td><center><b>Topic</b></center></td><td><center><b>Total question</b></center></td><td><center><b>Marks</b></center></td><td><center><b>Action</b></center></td></tr>';
                        $c=1;
                        while($row = mysqli_fetch_array($result)) {
                            $title = $row['title'];
                            $total = $row['total'];
                            $sahi = $row['sahi'];
                            $eid = $row['eid'];
                            echo '<tr><td><center>'.$c++.'</center></td><td><center>'.$title.'</center></td><td><center>'.$total.'</center></td><td><center>'.$sahi*$total.'</center></td>
                            <td><center><b><a href="update.php?q=rmquiz&eid='.$eid.'" class="pull-right btn sub1" style="margin:0px;background:red;color:black"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Remove</b></span></a></b></center></td></tr>';
                        }
                        $c=0;
                        echo '</table></div></div>';
                    }
                ?>

                <?php
                if (@$_GET['q'] == 6 && !(@$_GET['step'])) {
                    $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                    echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
                        <tr><td><center><b>S.N.</b></center></td><td><center><b>Topic</b></center></td><td><center><b>Total question</b></center></td><td><center><b>Marks</b></center></td><td><center><b>Action</b></center></td></tr>';
                    $c = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        $title = $row['title'];
                        $total = $row['total'];
                        $sahi = $row['sahi'];
                        $eid = $row['eid'];

                        // Fetch qid from the questions table based on the current eid
                        $questionsQuery = mysqli_query($con, "SELECT qid, sn FROM questions WHERE eid = '$eid'");
                        $questionsData = mysqli_fetch_array($questionsQuery);
                        $qid = $questionsData['qid'];
                        $sn = $questionsData['sn'];
                        //var_dump($qid, $sn);

                        echo '<tr>
                                <td><center>' . $c++ . '</center></td>
                                <td><center>' . $title . '</center></td>
                                <td><center>' . $total . '</center></td>
                                <td><center>' . $sahi * $total . '</center></td>
                                <td><center><b>
                                    <a href="edit_quiz.php?q=editquiz&eid=' . $eid . '&sn=' . $sn . '&qid=' . $qid . '" class="pull-right btn sub1" style="margin:0px;background:skyblue;color:black">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;
                                        <span class="title1"><b>Edit</b></span>
                                    </a>
                                </b></center></td>
                            </tr>';
                    }
                    $c = 0;
                    echo '</table></div></div>';
                }
                ?>


            </div>
        </div>
    </div>
</body>
</html>
