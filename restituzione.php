<?php session_start(); ?>

<?php include "functions.php"; ?>

<?php if($_SERVER["REQUEST_METHOD"] == "POST"): ?>
<?php $durata; ?>
<?php $effettuato = false; ?>
<?php restituzioneLibri(); ?>

<?php if($effettuato): ?>

<?php include "header.php"; ?>

        <div class="libroRestituito">
            <h1>Restituzione del libro effettuata con successo</h1>
            <h2>Hai tenuto in prestito il libro per <?php echo $durata; ?> giorni</h2>
            <form method="post" action="http://localhost/libri.php">
                <input type="submit" id="input5" class="btn" value="CONTINUA CON LA NAVIGAZIONE">
            </form>
        </div>


<?php include "footer.php"; ?>

<?php else: ?>
<?php include "header.php"; ?>
        <div class="libroRestituito">
            <h1>La restituzione del libro &egrave; gi&agrave; stata effettuata con successo</h1>
            <form method="post" action="http://localhost/libri.php">
                <input type="submit" id="input5" class="btn" value="CONTINUA CON LA NAVIGAZIONE">
            </form>
        </div>
<?php include "footer.php"; ?>
<?php endif; ?>
<?php else: ?>
<?php header("location: http://localhost/libri.php"); ?>
<?php endif; ?>