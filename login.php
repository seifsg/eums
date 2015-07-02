<?php
if(!isset($_SESSION))
    session_start();
if(isset($_POST['password'])){
    require_once('includes/functions.php');
     get_db_ready();
     try_to_login($_POST['username'],$_POST['password'],'users');
     if(isset($_SESSION['userpass']))
        header('Location: index.php');
     else
        $message = "login error";
     
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>login</title>
</head>
    <link rel="stylesheet" type="text/css" href="style/main.css" >
<body>
    <div id="loginform">
        <label>Login Form</label>
        <?php if(isset($message)){
                echo '<span style="color: red;">'.$message.'</span>';
               }
        ?>
        <form id="login" action="login.php" method="post">
            <input type="text" id="username" name="username" placeholder="Enter your username here...">
            <input type="password"  id="password" name="password" >
            <input type="submit" value="login" id="login-button" >
        </form>
    </div>
</body>
</html>