<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Some Random and Incomplete Game</title>
    <script src="script.js" type="module" defer></script>
    <link rel="stylesheet" href="style.css">
    <style>
        form,form>*{
            display: none;
        }
    </style>
</head>
<body>
    <?php
    if(isset($_GET['action']) ){
        unset($_SESSION['user']);
    }
    if(!isset($_SESSION['user'])){
        header('Location: Login\Login.php');
    }
    if(!isset($_POST['playagain'])){
        if(isset($_GET['score'])){
            submitScore();
            $_SESSION['score'] = $_GET['score'];
        }
        else{
            $_GET['score'] = 0;
        }
    }
    showContent();
    showForm();
    ?>
</body>
<?php
function showContent(){
    if(!isset($_GET['score'])){
        $_GET['score'] = 0;
    }
    $scores = getScores();
    echo <<<CONTENT
    <div class='container'>
        <div class="gamecontainer">
            <canvas class="game"></canvas>
        </div>
        <div class='utilities'>
            <div class="leaderboards">
                <h1 style="border-radius: 0.5em;">LeaderBoards</h1>
                <div class="scores">
                    <div class='players'>
                        <div style='color:rgb(156, 208, 238)'>Username</div>
                        <div style='color:rgb(156, 208, 238)'>Score</div>
                    </div>
                    $scores
                </div>
            </div>
            <div class="userinterface">
                <div class='userscore' value='0' name='userscore'>
                    <p style='color:rgb(156, 208, 238);margin-top:4% !important'>Your Score</p>
                    <p>0</p>
                </div>
                <div class='previousscore'>
                    <p style='color:rgb(156, 208, 238)'>Previous Score</p>
                    <p>$_GET[score]</p>
                </div>
            </div>
        </div>
    </div>
    CONTENT;
}
function getScores(){
    if(!$conn = mysqli_connect('localhost','root','','game')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    $query = "SELECT * From scores
                ORDER BY score DESC
                LIMIT 10;";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't fetch Data ".mysqli_error($conn));
    }
    $i = 1;
    $scores = "";
    while($row = mysqli_fetch_assoc($result)){
        $scores .= "<div class='players'><div id='player$i'>";
        $scores .= implode("</div><div>",$row);
        $scores .= "</div></div>";
        $i++;
    }
    return $scores;
}

function showForm(){
    echo <<<FORM
        <form method='POST'>
            <div class='displayscore'></div>
            <div class='buttons'>
                <input class='playagain' type='submit' value='Play Again!' name='playagain'>
                <div class='border'></div>
                <a id='sendscore' href='$_SERVER[PHP_SELF]?'>Submit Score and Play Again!</a>
            </div>
            <a href='$_SERVER[PHP_SELF]?action=logout'>Log Out</a>
        </form>
    FORM;
}

function submitScore(){
    if(!$conn = mysqli_connect('localhost','root','','game')){
        die("Could not connect to database ".mysqli_connect_error());
    }
    $query = "SELECT * FROM scores
                WHERE username='$_SESSION[user]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't submit score ".mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    if($row != null){
        $query = "UPDATE scores
                SET score = '$_GET[score]'
                WHERE username='$_SESSION[user]'";
        if(!$result = mysqli_query($conn,$query)){
            die("Couldn't submit score ".mysqli_error($conn));
        }
    }
    else{
        $query = "INSERT INTO scores
                    VALUES('$_SESSION[user]',$_GET[score])";
        if(!$result = mysqli_query($conn,$query)){
            die("Couldn't submit score ".mysqli_error($conn));
        } 
    }
    
}

?>
</html>