<?php session_start(); ?>
<?php
if(isset($_POST['daLogin'])){
    session_destroy();
    header("location: http://localhost/login.php");
    exit;
}else{
    session_destroy();
    header("location: http://localhost/home.php");
    exit;
}
?>