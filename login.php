<?php session_start(); ?>

<?php include "functions.php"; ?>
<?php $errori = array(); ?>
<?php verificaDati(); ?>

<?php if(!empty($_SESSION['username'])): ?>
<?php include "header.php"; ?>

<div class="conferma">
    <h1>Sei connesso con <?php echo $user; ?></h1>
    <h2>Continua la navigazione per prendere in prestito o restituire i libri</h2>
    
    <div class="effettuaLogout">
        <h3 style="display: inline">Non sei <?php echo $user; ?> &#63;</h3>
        <form style="display: inline" method="post" action="http://localhost/logout.php">
            <input type="submit" id="input3" class="btn1" value="EFFETTUA IL LOGOUT" name="daLogin">
        </form>
    </div>
    
</div>

<?php include "footer.php"; ?>
<?php endif; ?>

<?php if(empty($_SESSION['username'])): ?>

<?php
if(isset($_POST['pulisci'])){
    $nome = "";
}
else{
    if(isset($_COOKIE['username'])){
    $nome = $_COOKIE['username'];
    }else{
        $nome = "";
    }
}
?>

<?php include "header.php"; ?>

        <div class="messaggiErrore" style="<?php if(empty($errori)) {echo 'display: none';} ?>">
            <h4>Messaggio di errore</h4>
            <div class="messaggio">
                <?php foreach($errori as $text) {echo '<li style="color: red">'.$text.'</li>';} ?>
            </div>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form">
            <div class="contenitore">
            
                <h1>Login</h1>
                <label for="email"><b>Username:</b></label>
                <input type="text" placeholder="Inserisci Username" name="user" value="<?php echo $nome?>">

                <label for="psw"><b>Password:</b></label>
                <input type="password" placeholder="Inserisci Password" name="psw">
           
            </div>
            <div class="pulsanti">
                <input type="submit" name="submit" id="input1" class="btn" value="OK">
                <input type="submit" name="pulisci" id="input2" class="btn" value="PULISCI">
        
            </div>
        </form>
        
<?php include "footer.php"; ?>
<?php endif; ?>