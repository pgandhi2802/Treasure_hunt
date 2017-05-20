<?php
    include 'connect.php';
    session_start();
    if($_SESSION['loggedin'])
    {
         session_destroy();
         $_SESSION['loggedin']=false;
         $_SESSION['name']=null;
         $_SESSION['user']=0;
    }
        header("location:login.php");
?>