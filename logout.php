<?php
session_start();
if(isset($_GET['key']) && $_GET['key'] == 'M1g4Vlk2QllHOFFJTDA3amt1cWpsUnNKOmhoWkNlSEg0SzFuVVJ2')
{
    session_unset();
    header('Location: login.php');
}
else
{
    header('Location: login.php');
}
?>