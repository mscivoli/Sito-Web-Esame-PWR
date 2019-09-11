<?php session_start(); ?>
<?php if(!isset($_SESSION['registrazioneAvvenuta'])){
    header("location: http://localhost/new.php");
    exit;
}else{
    unset($_SESSION['registrazioneAvvenuta']);
}
?>

<?php include "header.php"; ?>

        <div class="registrazione">
            <h1>Registrazione avvenuta con successo</h1>
        </div>

        <div class="pulsanti">
            <form method="post" action="http://localhost/login.php">
                <input type="submit" id="input2" class="btn" value="EFFETTUA IL LOGIN">
            </form>
        </div>

<?php include "footer.php"; ?>