<?php 
if(!empty($_SESSION['username'])){
    $user = $_SESSION['username'];
    
    //APRO LA CONNESSIONE PER IL NUMERO DEI LIBRI IN PRESTITO
    
    $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
    if($letturaDB->connect_error){
        die("Connection failed: " . $letturaDB->connect_error);
    }
    
    //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
    $stmt = $letturaDB -> prepare("SELECT * FROM books WHERE prestito = ?");
    $stmt -> bind_param("s" , $user);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $stmt -> close();
    
    $letturaDB -> close();
    
    if($result->num_rows === 0){
        $numLibri = 0;
    }else{
        $numLibri = $result->num_rows;
    }
    
}else{
    $user = "ANONIMO";
    $numLibri = 0;
}
?>

<!DOCTYPE>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Biblioteca B. Pascal - prestito su oltre 500 libri">
        <title>Biblioteca B. Pascal</title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <meta name="keywords" content="prestito libri biblioteca pascal">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0">
        
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="print.css" media="print">
        
    </head>
    <body>
        
        <div class="myheader">
            <h1>Biblioteca B. Pascal</h1>
            <p>Prestito Libri</p>
        </div>
        <div class="navbar">
            <a id="utente"><i class="fa fa-fw fa-user"></i> <?php echo $user ?> (<?php echo "libri: ".$numLibri ?>)</a>
            <a href="home.php">HOME</a>
            <a href="libri.php">LIBRI</a>
            <a href="new.php">NEW</a>
            <?php if(isset($_SESSION['username'])) echo "<a id='log'>LOGIN</a>"; else echo "<a href='login.php'>LOGIN</a>"; ?>
            <?php if(isset($_SESSION['username'])) echo "<a href='logout.php'>LOGOUT</a>"; else echo "<a id='log'>LOGOUT</a>"; ?>
        </div>