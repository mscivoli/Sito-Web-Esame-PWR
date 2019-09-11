<?php session_start(); ?>

<?php include "functions.php"; ?>
<?php $errori = array(); ?>
<?php nuovaRegistrazione(); ?>

<?php include "header.php"; ?>

        <div class="messaggiErrore" style="<?php if(empty($errori)) {echo 'display: none';} ?>">
            <h4>Messaggio di errore</h4>
            <div class="messaggio">
                <?php foreach($errori as $text) {echo '<li style="color: red">'.$text.'</li>';} ?>
            </div>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form">
            <div class="contenitore">
            
                <div class="cnt1">
                    <h1>Registrati</h1>
                    <label for="email"><b>Username&#58;</b></label>
                    <input type="text" placeholder="Inserisci Username" name="user">
                </div>
                
                <div class="cnt2">
                    <div class="password1">
                        <label for="psw"><b>Password&#58;</b></label>
                        <input type="password" placeholder="Inserisci Password" name="psw">
                    </div>
                    <div class="password2">
                        <label for="psw"><b>Conferma Password&#58;</b></label>
                        <input type="password" placeholder="Conferma Password" name="pswConferma">
                    </div>
                </div>
           
            </div>
            <div class="pulsanti">
                <input type="submit" name="submit" id="input1" class="btn" value="REGISTRATI">
            </div>
        </form>        
        
<?php include "footer.php"; ?>

