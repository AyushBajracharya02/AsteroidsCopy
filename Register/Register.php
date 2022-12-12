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
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding: 0 0 2% 0;
        }
        .header{
            grid-column: 1/-1;
            display: flex;
            justify-content: center;
        }
        h1{
            opacity: 0.8;
            margin: 0;
            font-weight: 100;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 3em;
        }
        .inputsection{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        h2{
            opacity: 0.8;
            margin: 0;
            font-weight: 100;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 2em;
        }
        input{
            padding: 0 5%;
            border: 1px solid rgba(0,0,0,0.5);
            border-radius: 0.5em;
            width: 13em;
            height: 3em;
        }
        .inputsection:nth-child(6){
            grid-column: 1/-1;
        }
        .inputsection:nth-child(6)>input{
            grid-column: 1/-1;
            width: 20em;
        }
        .submit{
            padding: 0;
            border: none;
            width: 90%;
            margin: 0;
            background-color: #0c151e;
            border-radius: 1em;
            opacity: 0.8;
            color: white;
            font-size: 1em;
            padding: 0;
        }
        a{
            padding: 1%;
            opacity: 0.8;
            background-color: #0c151e;
            text-decoration: none;
            color: white;
        }
        .error{
            margin-top: 2% !important;
            font-size: 0.9em;
            text-align: center;
            line-height: 0.9;
            min-height: 1.2em;
            margin: 0;
            color: red;
            background-color: rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <?php
    if(!isset($_POST['submit'])){
        showForm(array('firstname'=>'','lastname'=>'','password'=>'','username'=>'','email'=>''));
    }
    else{
        sanitizedata();
        $errors = validate();
        if(count(array_filter($errors))){
            showForm($errors);
        }
        else{
            submitData();
            header("Location: ..\Login\Login.php");
        }
    }
    ?>
</body>
<?php

function showForm($errors){
    echo <<<FORM
    <form action="" class="container" method="post">
        <div class='header'>
            <h1>Create Account</h1>
        </div>
        <div class='inputsection'>
            <label for="firstname"><h2>First Name</h2></label>
            <input type="text" name="firstname" id="">
            <p class='error'>$errors[firstname]</p>
        </div>
        <div class='inputsection'>
            <label for="lastname"><h2>Last Name</h2></label>
            <input type="text" name="lastname" id="">
            <p class='error'>$errors[lastname]</p>
        </div>
        <div class='inputsection'>
            <label for="username"><h2>Username</h2></label>
            <input type="text" name="username" id="">
            <p class='error'>$errors[username]</p>
        </div>
        <div class='inputsection'>
            <label for="password"><h2>Password</h2></label>
            <input type="password" name="password" id="">
            <p class='error'>$errors[password]</p>
        </div>
        <div class='inputsection'>
            <label for="email"><h2>E-mail</h2></label>
            <input type="email" name="email" id="">
            <p class='error'>$errors[email]</p>
        </div>
        <div class='inputsection'>
            <input class='submit' type='submit' value='Create Account' name='submit'>
        </div>
        <div class='inputsection'>
            <a href='..\Login\Login.php'>I already have an Account</a>
        </div>
    </form>
    FORM;
}
function sanitizeData(){
    foreach($_POST as $key => $value){
        $_POST[$key] = htmlentities(trim($value));
    }
}
function validate(){
    if(!$conn = mysqli_connect('localhost','root','','game')){
        die("Couldn't connect to database ".mysqli_connect_error());
    }
    $errors = array('firstname'=>'','lastname'=>'','password'=>'','username'=>'','email'=>'');
    if(strlen($_POST['firstname'])<2 || strlen($_POST['firstname'])>32){
        $errors['firstname'] = "First Name must be between 2 <br/> and 32 characters.";
    }
    if(strlen($_POST['lastname'])<2 || strlen($_POST['lastname'])>32){
        $errors['lastname'] = "Last Name must be between 2 <br/> and 32 characters.";
    }
    if(strlen($_POST['username'])<2 || strlen($_POST['username'])>32){
        $errors['username'] = "Username must be between 2 <br/> and 32 characters.";
    }
    else{
        $query = "SELECT * FROM user
                    WHERE username = '$_POST[username]'";
        if(!$result = mysqli_query($conn,$query)){
            die("Couldn't fetch result from database ".mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        if($row != null){
            $errors['username'] = "Username already taken.";
        }
    }
    if(strlen($_POST['password'])<8 || strlen($_POST['password'])>32){
        $errors['password'] = "Username must be between 8 <br/> and 32 characters";
    }
    mysqli_close($conn);
    return $errors;
}
function submitData(){
    if(!$conn = mysqli_connect('localhost','root','','game')){
        die("Couldn't connect to database ".mysqli_connect_error());
    }
    $query = "INSERT INTO user
            VALUES('$_POST[username]','$_POST[password]','$_POST[firstname]','$_POST[lastname]','$_POST[email]')";
    if(!$result = mysqli_query($conn,$query)){
        die("Couldn't fetch result from database ".mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>
</html>