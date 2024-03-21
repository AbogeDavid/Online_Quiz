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
                if (@$_GET['q'] == 1) {
                    echo '<div class="panel">';
                    echo '<h2>Welcome to the ICT Seminar Challenge!</h2>';
                    echo '<div class="row">';

                    // Button for Tech Trivia
                    echo '<div class="col-md-6 center">';
                    
                    echo'<p>This challenge contains two sections, Tech Trivia and Digital Scavenger Hunt!!</p>';
                    echo'<p>You must pass the Tech Trivia first before proceeding to the Digital Scavenger Hunt!</p>';
                    echo'<p><strong>The Digital Scavenger Hunt can only be taken once!</strong></p>';
                    echo '<a href="welcome.php?q=6&t=" class="btn sub1" style="color:black;margin:0px;background:#1de9b6" onclick="enableScavengerHunt()">';
                    
                    echo '<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Tech Trivia</b></span></a>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>'; 
                    echo '</div>'; 
                    echo '<style>';
                    echo '.navbar { display: none; }';
                    echo '</style>';
                } 
            ?>

            <script>
                // Function to enable the Digital Scavenger Hunt button
                function enableScavengerHunt() {
                    document.getElementById('scavengerHuntBtn').style.background = '#1de9b6'; // Enable the button
                    document.getElementById('scavengerHuntBtn').style.pointerEvents = 'auto'; // Make the button clickable
                }
            </script>

<?php if(@$_GET['q']==6) 
{
    echo '
    <div class="start">
        <h1>Welcome to our ICT seminar Trivia!</h1>
        <a class="btn" href="#" onclick="startQuiz()">Get Started</a>
    </div>

    <div class="quiz" style="display: none;">
        <p class="question"></p>
        <input type="text" class="answer" placeholder="Type your answer">
        <a class="sub" href="#" onclick="checkAnswer()">Submit</a>
        <a class="sub" href="#" onclick="showPreviousQuestion()">Previous</a>

        <div class="progress" style="height: 20px; width: 80%; padding: 0px; margin-top: 10%; margin-left: 10%;">
        <div id="progressFill" class="progress-bar bg-success" style="width: 0%; transition: width 0.3s ease-in-out;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="summary" style="display: none;">
        <h2>Result Summary</h2>
        <p>Congrats you scored <span id="score">0</span> out of <span id="totalQuestions"></span> correct!</p>
        <a id="proceedBtn" class="proceed" href="#" onclick="proceedToNext()" style="display: none;">Proceed</a>
        <a id="retryBtn" class="proceed" href="#" onclick="retryQuestions()" style="color: red; display: none;">Retry</a>
    </div>

    <style> 
        .navbar { display: none; }
    </style>

    <script>
        var questions = ' . json_encode(getQuestions()) . '; // Call a function to get dynamic questions
        let score = 0;
        let currentQuestion = 0;
        let userAnswers = [];

        function startQuiz() {
            console.log("startQuiz function is called!");
            $(".start").hide();
            $(".quiz").show();
            showQuestion();
            $("#progressFill").width("0%"); // Initialize the width of the progress bar to 0%
            $("#totalQuestions").text(questions.length);
            $("#questionCount").text(currentQuestion + 1);
        }
        
        function showPreviousQuestion() {
            if (currentQuestion > 0) {
                currentQuestion--;
                const userAnswer = userAnswers[currentQuestion];
                $(".answer").val(userAnswer);
        
                showQuestion();
            }
        }
        
        function showQuestion() {
            if (currentQuestion !== 0) { 
                $("#progressFill").width((100 / questions.length * (currentQuestion + 1)) + "%");
            }
            let question = questions[currentQuestion];
            $(".question").text(question.title);
            $(".answer").val(""); 
            $("#proceedBtn").hide(); 
            $("#retryBtn").hide();

        }
        function checkAnswer() {
            console.log("checkAnswer function is called!");
            let question = questions[currentQuestion];
            console.log("Current Question:", question);
            let userAnswer = $(".answer").val().trim();
        
            // Convert all correct answers to lowercase for case-insensitive comparison
            let correctAnswers = question.answers.map(answer => answer.toLowerCase());
            console.log("Correct Answers:", correctAnswers);
        
            if (correctAnswers.includes(userAnswer.toLowerCase())) {
                score++;
                console.log("Correct Answer!");
                showPopup("Correct!", "green");
            } else {
                console.log("Incorrect Answer!");
                showPopup("Wrong!", "red");
            }
        
            currentQuestion++;
        
            if (currentQuestion >= questions.length) {
                showSummary();
            } else {
                showQuestion();
            }
        }
        
        

        function showSummary() {
            $(".quiz").hide();
            $(".summary").show();
            $("#score").text(score);
            $("#totalQuestions").text(questions.length);

            // Show the Proceed button if all answers are correct
            if (score === questions.length) {
                $("#proceedBtn").show();
            } else {
                $("#retryBtn").show();
            }
        }

        function proceedToNext() {
            window.location.href = "welcome.php?q=5";
        }

        function retryQuestions() {
            alert("You have to pass the tech Trivia first!");
            window.location.href = "welcome.php?q=6";
        }
        function showPopup(message, color) {
            // Create a popup element dynamically
            const popup = $("<div>", {
                class: "popup",
                text: message,
                css: {
                    background: color,
                    color: "white",
                    padding: "10px",
                    borderRadius: "5px",
                    position: "fixed",
                    top: "50%",
                    left: "50%",
                    transform: "translate(-50%, -50%)",
                },
            });
    
            // Append the popup to the body
            $("body").append(popup);
    
            // Remove the popup after a short delay (e.g., 1500 milliseconds)
            setTimeout(() => {
                popup.remove();
            }, 1500);
        }
    </script>';
}?>

<?php
function getQuestions() {
    // You can modify this function to fetch questions from a database or any other source
    return [
        [
            'title' => '1. What is the name of the current Cabinet Secretary for the Ministry of ICT in Kenya?',
            'answers' => ['Eliud Owalo']
        ],
        [
            'title' => "2. Which Kenyan city is often referred to as the \"Silicon Savannah\" due to its growing
            tech and innovation ecosystem?",
            'answers' => ["Nairobi"]
        ],
        [
            'title' => "3. In Kenya, what is the name of the government's digital literacy program aimed at
            empowering citizens with digital skills?",
            'answers' => ["digital literacy program"]
        ],
        [
            'title' => "4. Which Kenyan government initiative aims to provide affordable laptops to primary
            school children for educational purposes?",
            'answers' => ["digital literacy program"]
        ],
        [
            'title' => "5. What is the name of Kenya's national digital identification system that assigns a
            unique number to each citizen?",
            'answers' => ["huduma namba"]
        ],
        [
            'title' => "6. Which Kenyan company is known for its mobile-based money transfer and financial
            services, widely used for digital transactions in the country?",
            'answers' => ["safaricom"]
        ],
        [
            'title' => "7. What is the name of the government initiative in Kenya that seeks to provide
            internet access to underserved areas through the National Optic Fiber Backbone Infrastructure
            (NOFBI)?",
            'answers' =>  ["The Last Mile Connectivity Project"]
        ],
        [
            'title' => "8. In 2019, Kenya unveiled Africa's largest single wind farm. What is the name of this
            wind farm located in Turkana County?",
            'answers' =>  ["Lake Turkana Wind Power Project"]
        ]
    ];
}
?>

                <?php if(@$_GET['q']==5) 
                {
                    $result = mysqli_query($con,"SELECT * FROM quiz WHERE type = 2 ORDER BY date DESC") or die('Error');
                    echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
                    <tr><td><center><b>S.N.</b></center></td><td><center><b>Topic</b></center></td><td><center><b>Total question</b></center></td><td><center><b>Marks</center></b></td><td><center><b>Action</b></center></td></tr>';
                    $c=1;
                    while($row = mysqli_fetch_array($result)) {
                        $title = $row['title'];
                        $total = $row['total'];
                        $sahi = $row['sahi'];
                        $eid = $row['eid'];
                        $type = $row['type'];
                    $q12=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error98');
                    $rowcount=mysqli_num_rows($q12);	
                    if($rowcount == 0){
                        echo '<tr><td><center>'.$c++.'</center></td><td><center>'.$title.'</center></td><td><center>'.$total.'</center></td><td><center>'.$sahi*$total.'</center></td><td><center><b><a href="welcome.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="btn sub1" style="color:black;margin:0px;background:#1de9b6"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></center></td></tr>';
                    }
                    else
                    {
                    echo '<tr style="color:#99cc32"><td><center>'.$c++.'</center></td><td><center>'.$title.'&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></center></td><td><center>'.$total.'</center></td><td><center>'.$sahi*$total.'</center></td><td><center><b><a href="welcome.php?q=5" class="pull-right btn sub1" style="color:black; margin:0px; background:green" onclick="showAlert();">
                    <span aria-hidden="true"></span>&nbsp;<span class="title1"><b>Done</b></span>
                </a></center></td></tr>';
                ?>
                <script>
                    function showAlert() {
                        alert("You have already taken the Challenge!");
                    }
                </script>
                <?php
                    }
                    }
                    $c=0;
                    echo '</table></div></div>';
                }?>

                <?php
                    if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) 
                    {
                        $eid=@$_GET['eid'];
                        $sn=@$_GET['n'];
                        $total=@$_GET['t'];
                        $q=mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' " );
                        echo '<div class="panel" style="margin:5%">';
                        while($row=mysqli_fetch_array($q) )
                        {
                            $qns=$row['qns'];
                            $qid=$row['qid'];
                            echo '<b>Question &nbsp;'.$sn.'&nbsp;::<br /><br />'.$qns.'</b><br /><br />';
                        }
                        $q=mysqli_query($con,"SELECT * FROM options WHERE  qid='$qid' " );
                        echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$sn.'&t='.$total.'&qid='.$qid.'" method="POST"  class="form-horizontal">
                        <br />';

                        while($row=mysqli_fetch_array($q) )
                        {
                            $option=$row['option'];
                            $optionid=$row['optionid'];
                            echo'<input type="radio" name="ans" value="'.$optionid.'">&nbsp;'.$option.'<br /><br />';
                        }
                        echo'<br /><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button></form></div>';
                    }

                    if(@$_GET['q']== 'result' && @$_GET['eid']) 
                    {
                        $eid=@$_GET['eid'];
                        $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
                        echo  '<div class="panel">
                        <center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

                        while($row=mysqli_fetch_array($q) )
                        {
                            $s=$row['score'];
                            $w=$row['wrong'];
                            $r=$row['sahi'];
                            $qa=$row['level'];
                            echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>'.$qa.'</td></tr>
                                <tr style="color:#99cc32"><td>Right Answer(s)&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>'.$r.'</td></tr> 
                                <tr style="color:red"><td>Wrong Answer(s)&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>'.$w.'</td></tr>
                                <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
                               
                        }
                        echo '</table></div>';
                        echo '<div style="text-align: center; margin-top: 20px;">
                        <form action="showanswer.php" method="POST">
                            <button type="submit" name="showanswer" class="btn btn-info">Show Answers</button>
                        </form>
                    </div>';
                       
                    }
                ?>
                <?php
                    if(@$_GET['q']== 2) 
                    {
                        $q=mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC " )or die('Error197');
                        echo  '<div class="panel title">
                        <table class="table table-striped title1" >
                        <tr style="color:black;"><td><center><b>S.N.</b></center></td><td><center><b>Quiz</b></center></td><td><center><b>Question Solved</b></center></td><td><center><b>Right</b></center></td><td><center><b>Wrong<b></center></td><td><center><b>Score</b></center></td>';
                        $c=0;
                        while($row=mysqli_fetch_array($q) )
                        {
                        $eid=$row['eid'];
                        $s=$row['score'];
                        $w=$row['wrong'];
                        $r=$row['sahi'];
                        $qa=$row['level'];
                        $q23=mysqli_query($con,"SELECT title FROM quiz WHERE  eid='$eid' " )or die('Error208');

                        while($row=mysqli_fetch_array($q23) )
                        {  $title=$row['title'];  }
                        $c++;
                        echo '<tr><td><center>'.$c.'</center></td><td><center>'.$title.'</center></td><td><center>'.$qa.'</center></td><td><center>'.$r.'</center></td><td><center>'.$w.'</center></td><td><center>'.$s.'</center></td></tr>';
                        }
                        echo'</table></div>';
                    }

                    if(@$_GET['q']== 3) 
                    {
                        $q=mysqli_query($con,"SELECT * FROM rank ORDER BY score DESC " )or die('Error223');
                        echo  '<div class="panel title"><div class="table-responsive">
                        <table class="table table-striped title1" >
                        <tr style="color:red"><td><center><b>Rank</b></center></td><td><center><b>Name</b></center></td><td><center><b>Email</b></center></td><td><center><b>Score</b></center></td></tr>';
                        $c=0;

                        while($row=mysqli_fetch_array($q) )
                        {
                            $e=$row['email'];
                            $s=$row['score'];
                            $q12=mysqli_query($con,"SELECT * FROM user WHERE email='$e' " )or die('Error231');
                            while($row=mysqli_fetch_array($q12) )
                            {
                                $name=$row['name'];
                            }
                            $c++;
                            echo '<tr><td style="color:black"><center><b>'.$c.'</b></center></td><td><center>'.$name.'</center></td><td><center>'.$e.'</center></td><td><center>'.$s.'</center></td></tr>';
                        }
                        echo '</table></div></div>';
                    }
                ?>
</body>
</html>