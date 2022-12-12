<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            box-sizing: border-box;
        }
        body{
            margin: 0;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-image: url('https://www.grid.news/resizer/hWvBPn345AlGL-yiSJ0N1YYHf9A=/arc-photo-thesummit/arc2-prod/public/D2YP5VNBIJGXVCEC66MLUFZPIA.jpeg');
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container{
            color: white;
            height: 80%;
            width: 30%;
            border-radius: 1em;
            background-color: rgba(0, 0, 0, 0.5);
            border: 0.1em solid white;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            padding: 1% 0;
        }
        h1{
            opacity: 0.8;
            margin: 0;
            font-weight: 100;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 3.5em;
        }
        .username-input-area,.password-input-area,.submit-area{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2{
            opacity: 0.8;
            margin: 0;
            font-weight: 100;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 2em;
        }
        .username-input-area>*,.password-input-area>*,.submit-area>*{
            margin: 5%;
        }
        input{
            width: 20em;
            height: 3em;
        }
        .submit{
            height: 2em;
            width: 6em;
            margin: 0;
            background-color: #0c151e;
            border-radius: 1em;
            opacity: 0.8;
            color: white;
            font-size: 1.5em;
            padding: 0;
        }
        a{
            text-decoration: none;
            color: white;
        }
        .error{
            min-height: 1.2em;
            margin: 0;
            color: red;
            background-color: rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <?php
    if(isset($_SESSION['user'])){
        header('Location: ..\index.php');
    }
    if(!isset($_POST['submit'])){
        showForm("");
    }
    else{
        sanitizedata();
        if($error = validate()){
            showForm($error);
        }
        else{
            $_SESSION['user'] = $_POST['username'];
            header("Location: ..\index.php");
        }
    }
    ?>
</body>
<?php
function showForm($error){
    echo <<<FORM
    <form class="container" action="$_SERVER[PHP_SELF]" method="post">
        <h1>Login</h1>
        <div class="username-input-area">
            <label for="username"><h2>Username</h2></label>
            <input type="text" name="username" id="">
        </div>
        <div class="password-input-area">
            <label for="password"><h2>Password</h2></label>
            <input type="password" name="password" id="">
        </div>
        <p class='error'>$error</p>
        <div class="submit-area">
            <input name='submit' class="submit" type="submit" value="Log-in">
            <a href="..\Register\Register.php" style='width:max-content'>Create account</a>
        </div>
    </form>
    FORM;
}
function sanitizeData(){
    foreach($_POST as $key => $value){
        $_POST[$key] = htmlentities($value);
    }
}
function validate(){
    $error = "";
    if(!$conn = mysqli_connect('localhost','root','','game')){
        die("Couldn't connect to database ".mysqli_connect_error());
    }
    $query = "SELECT * FROM user
                WHERE username='$_POST[username]' AND password='$_POST[password]'";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't fetch result from database ".mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    if($row == null){
        $error = "Invalid Username or Password";
    }
    mysqli_close($conn);
    return $error;
}
?>
</html>