<?php session_start(); ?>

<?php include "header.php"; ?>

<?php include "functions.php"; ?>

<?php $disponibili; ?>
<?php $totali; ?>
<?php libriDisponibili(); ?>
<?php libriTotali(); ?>

<?php if(!empty($_SESSION['username'])): ?>

<?php $errori = array(); ?>

<?php 
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['prestito'])){
    
    if(!empty($_POST['numGiorni'])){
        if(filter_var($_POST['numGiorni'], FILTER_VALIDATE_INT) && $_POST['numGiorni'] > 0){
            $giorni = $_POST['numGiorni'];
            $utente = $_SESSION['username'];
            $scelte = isset($_POST['scelta']) ? $_POST['scelta'] : array();
    
            if (!count($scelte)) $errori[] = "Non hai selezionato nessun libro";
            elseif (count($scelte) > (3-$numLibri)) $errori[] = "Non puoi avere pi&ugrave; di 3 libri in prestito";
            else {
                foreach($scelte as $scelta) {
                    $scritturaDB = new mysqli("localhost", "uReadWrite", "SuperPippo!!!", "biblioteca");

                    if($scritturaDB->connect_error){
                        die("Connection failed: " . $scritturaDB->connect_error);
                    }
            
                    $stmt =  $scritturaDB -> prepare("UPDATE books SET prestito = ? , data = NOW(), giorni = ? WHERE id = ?");
                    $stmt -> bind_param("sii", $utente, $giorni, $scelta);
                    $stmt -> execute();
                    $stmt -> close();
            
                    $scritturaDB -> close();

                }
                header ("location: http://localhost/libri.php");
                exit;
            }
        }else{
            $errori[] = "il valore non &egrave; un intero, inserire un valore valido e riprovare";
            $scelte = isset($_POST['scelta']) ? $_POST['scelta'] : array();
            if (!count($scelte)) $errori[] = "Non hai selezionato alcun libro";
        }
    }else{
        $errori[] = "Non hai selezionato il numero di giorni: devi inserire almeno 1 giorno";
        $scelte = isset($_POST['scelta']) ? $_POST['scelta'] : array();
        if (!count($scelte)) $errori[] = "Non hai selezionato alcun libro";
    }
}
?>
         
<?php $prestito; ?>
<?php calcoloLibriPrestito(); ?>

<?php $errore = ""; ?>


        <form method="post" action="http://localhost/restituzione.php">
            <div class="infoPrestiti">
                <h2>I tuoi libri in prestito</h2>
                <div class="prestito">
        
                    <?php if($prestito->num_rows === 0): ?>
                    <p>Al momento non hai alcun libro in prestito</p>
                    <?php else: ?>
        
                    <table>
                        <tr>
                            <th>Autore</th>
                            <th>Titolo</th>
                            <th>Prestito</th>
                        </tr>
            
                        <?php
                        while($riga = mysqli_fetch_array($prestito)){
                            echo "<tr>";
                            echo "<td>" . $riga['autori'] . "</td>";
                            echo "<td>" . $riga['titolo'] . "</td>";
                            if(time() - strtotime($riga['data']) > $riga['giorni'] * (60*60*24)){
                                echo "<td>PRESTITO SCADUTO</td>";
                            }else
                                echo "<td>IN PRESTITO</td>";
                            $idLibro = $riga['id'];
                            echo "<td id='input6'><input type='submit' id='input4' class='btn' name='$idLibro' value='RESTITUISCI'></td>";
                            echo "</tr>";
                        }
                        ?>
            
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="infoPrestiti">
                <h2>Libri disponibili</h2>
                <div class="prestito">
                    <?php if($disponibili->num_rows === 0): ?>
                    <p>Al momento non &egrave; disponibile alcun libro</p>
                    <?php else: ?>
                    
                    <table>
                        <tr>
                            <th>Autore</th>
                            <th>Titolo</th>
                        </tr>
                
                        <?php
                        while($riga = mysqli_fetch_array($disponibili)){
                            if(empty($riga['prestito']) && strtotime($riga['data']) < 0 && $riga['giorni'] == 0){
                                echo "<tr>";
                                echo "<td>" . $riga['autori'] . "</td>";
                                echo "<td>" . $riga['titolo'] . "</td>";
                                $idLibroLibero = $riga['id'];
                                echo "<td id='check' style='width: 3%'><input type='checkbox' name='scelta[]' value='$idLibroLibero'></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="erroriPrenotazioni" style="<?php if(empty($errori)) {echo 'display: none';} ?>">
                    <h3>Messaggio di errore</h3>
                    <?php foreach($errori as $text) {echo '<li style="color: red">'.$text.'</li>';} ?>
                </div>
            </div>
            <div class="inserimento" style="<?php if($disponibili->num_rows === 0) {echo 'display: none';} ?>">
                <h3>Inserisci il numero di giorni&#58; </h3><input type="text" id="testo" name="numGiorni">
                <input type="submit" class="btn" name="prestito" value="PRESTITO">
            </div>
        </form>

<?php endif; ?>

<?php if(empty($_SESSION['username'])): ?>

        <div class="richiestaLogin">
            <?php $numDisponibili = $disponibili -> num_rows; ?>
            <?php $numTotali = $totali -> num_rows; ?>

            <p>Numero totale di libri&#58; <?php echo $numTotali; ?></p>
            <p>Numero di libri disponibili&#58; <?php echo $numDisponibili; ?></p>
    
            <p>Effettua il LOGIN per accedere ai nostri servizi</p>

            <form method="post" action="http://localhost/login.php">
                <input type="submit" id="input5" class="btn" value="EFFETTUA IL LOGIN">
            </form>

        </div>

<?php endif; ?>

<?php include "footer.php"; ?>

























