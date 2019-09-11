<?php

function verificaDati(){
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){
        
        global $errori;
        
        if(empty($_POST['user'])){
            $errori[] = "Hai dimenticato di inserire l&rsquo;Username";
        }
        
        if(empty($_POST['psw'])){
            $errori[] = "Hai dimenticato di inserire la tua Password";
        }
        
        if(!empty($_POST['user']) && !empty($_POST['psw'])){
            
            //USO UNA FUNZIONE PER PREVENIRE ATTACCHI XSS
            
            $username = htmlspecialchars($_POST['user']);
            $password = htmlspecialchars($_POST['psw']);
            
            //CIFRATURA PASSWORD (TOLTA)
            
            //$hashPsw = "$2y$10$";
            //$saltPsw = "utilizzounalungastringa23";
            //$pswSicura = $hashPsw . $saltPsw;
            //$password = crypt($password , $pswSicura);

            //APRO LA CONNESSIONE
            
            $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
            if($letturaDB->connect_error){
                die("Connection failed: " . $letturaDB->connect_error);
            }
    
            //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
            $stmt = $letturaDB -> prepare("SELECT * FROM users WHERE username = ? AND pwd = ?");
            $stmt -> bind_param("ss" , $username, $password);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $stmt -> close();
            
            $letturaDB -> close();
    
            if($result->num_rows === 0){
                $errori[] = "Credenziali errate&#58; riprovare";
            }else{
                
                $nome = "username";
                $valore = $username;
                $scadenza = time() + (48*60*60);

                setcookie($nome, $valore , $scadenza);
        
                $_SESSION['username'] = $username;
            
                header("location: http://localhost/libri.php");
                exit;
            }
            
            
            
            
            
        }
    }
}


function nuovaRegistrazione(){
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){
        
        global $errori;
        
        //VERIFICO SE I TRE CAMPI DI INPUT SONO STATI INSERITI
        
        if(empty($_POST['user'])){
            $errori[] = "Hai dimenticato di inserire l&rsquo;Username";
        }
        
        if(empty($_POST['psw'])){
            $errori[] = "Hai dimenticato di inserire la tua Password";
        }
        
        if(empty($_POST['pswConferma'])){
            $errori[] = "Hai dimenticato di inserire la Password di conferma";
        }
        
        //SE SONO STATI INSERITI ALLORA VERIFICO SE IL NOME UTENTE ESISTE NEL DATABASE E SE LE PASSWORD COINCIDONO
        
        if(!empty($_POST['user']) && !empty($_POST['psw']) && !empty($_POST['pswConferma'])){
            
            
            $username = htmlspecialchars($_POST['user']);
            
            $password = htmlspecialchars($_POST['psw']);
            
            $passwordConferma = htmlspecialchars($_POST['pswConferma']);
            
            $comparazione = strcmp($password, $passwordConferma);
            
            //SE LE PASSWORD NON SONO UGUALI MANDO UN MESSAGGIO DI ERRORE
            
            if($comparazione != 0){
                $errori[] = "Le password non coincidono";
            }
            
            //VERIFICO SE L'USERNAME E LA PASSWORD RISPETTANO I REQUISITI
            
            $patternUsername = "/(^[a-zA-Z%]{1})((?=.*[a-zA-Z%])(?=.*[0-9]))[a-zA-Z0-9%]{2,5}$/";
            $patternPassword = "/(?=.*[a-z])(?=.*[A-Z])^[a-zA-Z]{4,8}$/";
            
            if(!preg_match($patternUsername, $username)){
                $errori[] = "L'Username deve inziare con una lettera o con il carattere %&#44 deve essere lungo da 3 a 6 caratteri e deve contenere almeno un numero e un carattere non numerico";
            }
            
            if(!preg_match($patternPassword, $password)){
                $errori[] = "La Password deve contenere solo caratteri alfabetici&#44 deve essere lunga da 4 a 8 caratteri e deve contenere almeno un carattere minuscolo e uno maiuscolo";
            }
            
            //VERIFICO SE L'UTENTE ESISTE NEL DATABASE
            
            $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
            if($letturaDB->connect_error){
                die("Connection failed: " . $letturaDB->connect_error);
            }
            
            //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
            $stmt = $letturaDB -> prepare("SELECT * FROM users WHERE username = ?");
            $stmt -> bind_param("s" , $username);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $stmt -> close();
            
            $letturaDB -> close();
            
            if($result->num_rows !== 0){
                $errori[] = "Nome utente gi&agrave; esistente";
            }
            
            //SE L'UTENTE NON ESISTE, LE PASSWORD COINCIDONO E RISPETTANO I REQUISITI ALLORA PROCEDO CON L'INSERIMENTO NEL DATABASE
    
            if($result->num_rows === 0 && $comparazione == 0 && preg_match($patternUsername, $username) && preg_match($patternPassword, $password)){
                
                    
                    //CIFRATURA PASSWORD (TOLTA)
                        
                    //$hashPsw = "$2y$10$";
                    //$saltPsw = "utilizzounalungastringa23";
                    //$pswSicura = $hashPsw . $saltPsw;
                    //$password = crypt($password , $pswSicura);

                    // CREARE NUOVI DATI IN TABELLA
                
                    $scritturaDB = new mysqli("localhost", "uReadWrite", "SuperPippo!!!", "biblioteca");

                    if($scritturaDB->connect_error){
                        die("Connection failed: " . $scritturaDB->connect_error);
                    }
            
                    $stmt = $scritturaDB -> prepare("INSERT INTO users(username , pwd) VALUES(?, ?)");
                    $stmt -> bind_param("ss" , $username ,  $password);
                    $stmt -> execute();
                    $stmt -> close();
        
                    if(!$scritturaDB){
                        //SETTAGGIO MESSAGGIO ERRORE
                        echo ("Messaggio di errore: " . mysqli_error($scritturaDB));
                    }else{
                        mysqli_close($scritturaDB);
                        $_SESSION['registrazioneAvvenuta'] = true;
                        header("location: http://localhost/registrazione_avvenuta.php");
                        exit;
                    }
                
                    $scritturaDB -> close();

            }
        }
    }
}

function restituzioneLibri(){
    
    global $durata;
    
    global $effettuato;
    
    $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
    if($letturaDB->connect_error){
        die("Connection failed: " . $letturaDB->connect_error);
    }
    
    //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
    $stmt = $letturaDB -> prepare("SELECT * FROM books");
    $stmt -> execute();
    $result = $stmt -> get_result();
    $stmt -> close();
            
    $letturaDB -> close();
    
    if($result -> num_rows > 0){
         while($riga = mysqli_fetch_array($result)){
            $variabile = $riga['id'];
            if(!empty($_POST[$variabile]) && !empty($riga['prestito']) && strtotime($riga['data']) > 0 && $riga['giorni'] > 0){
                
                $effettuato = true;
                
                $dateToday = date('Y-m-d H:i:s');
                $dateFine = $riga['data'];
                $durata = floor((strtotime($dateToday) - strtotime($dateFine)) / (86400));
                
                $scritturaDB = new mysqli("localhost", "uReadWrite", "SuperPippo!!!", "biblioteca");

                if($scritturaDB->connect_error){
                    die("Connection failed: " . $scritturaDB->connect_error);
                }
            
                $stmt =  $scritturaDB -> prepare("UPDATE books SET prestito = '' , data = 0, giorni = 0 WHERE id = ?");
                $stmt -> bind_param("i", $variabile);
                $stmt -> execute();
                $stmt -> close();
            
                $scritturaDB -> close();

                if(!$scritturaDB){
                    die("Impossibile eseguire" . mysqli_error($connessioneDB));
                }
            }
        }
    }
   
}

function calcoloLibriPrestito(){
    
    global $prestito;
    
    $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
    if($letturaDB->connect_error){
        die("Connection failed: " . $letturaDB->connect_error);
    }
    
    //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
    $stmt = $letturaDB -> prepare("SELECT * FROM books WHERE prestito = ?");
    $stmt -> bind_param("s" , $_SESSION['username']);
    $stmt -> execute();
    $prestito = $stmt -> get_result();
    $stmt -> close();
            
    $letturaDB -> close();
}

function libriDisponibili(){
    
    global $disponibili;
    
    $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
    if($letturaDB->connect_error){
        die("Connection failed: " . $letturaDB->connect_error);
    }
    
    $settaggio = '';
    
    //QUI USO I METODI DI OOP PERCHE' LI TROVO PIU' INTUITIVI E VELOCI
            
    $stmt = $letturaDB -> prepare("SELECT * FROM books WHERE prestito = ?");
    $stmt -> bind_param("s", $settaggio);
    $stmt -> execute();
    $disponibili = $stmt -> get_result();
    $stmt -> close();
            
    $letturaDB -> close();
}

function libriTotali(){
    
    global $totali;
    
    $letturaDB = new mysqli("localhost", "uReadOnly", "posso_solo_leggere", "biblioteca");
    
    if($letturaDB->connect_error){
        die("Connection failed: " . $letturaDB->connect_error);
    }
    
    $stmt = $letturaDB -> prepare("SELECT * FROM books");
    $stmt -> execute();
    $totali = $stmt -> get_result();
    $stmt -> close();
            
    $letturaDB -> close();
}

?>