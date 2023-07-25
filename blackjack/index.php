<?php
    error_reporting(E_ERROR | E_PARSE);
    session_start();
    // sprawdz_wygrana();
    $nazwy = ['C','D','T','P']; //czerwo, dzwonki, trefle, pik;
    $numery = ['2','3','4','5','6','7','8','9','10','A','K','Q','J'];
    if(!isset($_SESSION['karty'])){
        $_SESSION['karty'] = array();
        $a = 0;
        for($i=0;$i<count($nazwy);$i++){
            for($j=0;$j<count($numery);$j++){
                $_SESSION['karty'][$a] = $nazwy[$i]."/".$numery[$j];
                $a++;
                // echo $_SESSION['karty'][]."<br>";
            }
        }
    }
    if(!isset($_SESSION['gracz']) && !isset($_SESSION['komputer'])){
        $_SESSION['gracz'] = array();
        $_SESSION['komputer'] = array();
        if(count($_SESSION['gracz']) < 2 && count($_SESSION['komputer']) < 2){
            for($i=0;$i<2;$i++){
                // $_SESSION['gracz'][$i] = $nazwy[rand(0,3)]."/".$numery[rand(0,12)];
                dobierz_gracz($_SESSION['karty']);
                dobierz_komputer($_SESSION['karty']);
                // $_SESSION['komputer'][$i] = $nazwy[rand(0,3)]."/".$numery[rand(0,12)];
                
            }
        }
    }

    if(!isset($_SESSION['komputer_pass'])){
        $_SESSION['komputer_pass'] = false;
    }

    if(isset($_GET['dobierz'])){
        dobierz_gracz($karty);
    }

    if(isset($_GET['pass']) || isset($_GET['dobierz'])){
        odpal_ai($_SESSION['karty']);
    }

    if($_SESSION['punkty_komputer'] > 21 && $_SESSION['punkty_gracz'] > 21) $_SESSION['wygrana'] = 'r';


    function sprawdz_wygrana(){
        $_SESSION['punkty_komputer'] > $_SESSION['punkty_gracz'] ? $_SESSION['wygrana'] = 'k' : $_SESSION['wygrana'] = 'g';
    }

    if(!isset($_SESSION['punkty_gracz'])){
        $_SESSION['punkty_gracz'] = 0;
    }
    if(!isset($_SESSION['punkty_komputer'])){
        $_SESSION['punkty_komputer'] = 0;
    }

    if(isset($_GET["zakoncz"])){
        session_destroy();
        header('location: index.php');
    }

    // if(isset($_GET["pass"])){
    //     komputer_dobierz();
    // }


    function dobierz_komputer(&$karty){
        // foreach($_SESSION['komputer'] as $k){
        //     if($k == $nazwy[rand(0,3)]."/".$numery[rand(0,12)]){
        //         dobierz_komputer($nazwy,$numery);
        //     }
        // }
        // foreach($_SESSION['gracz'] as $g){
        //     if($g == $nazwy[rand(0,3)]."/".$numery[rand(0,12)]){
        //         dobierz_komputer($nazwy,$numery);
        //     }
        // }
        // $_SESSION['komputer'][count($_SESSION['komputer'])] = $nazwy[rand(0,3)]."/".$numery[rand(0,12)];
        
        $los = rand(0,count($_SESSION['karty'])-1);
        $_SESSION['komputer'][count($_SESSION['komputer'])] = $_SESSION['karty'][$los];
        
        $str = $_SESSION['komputer'][count($_SESSION['komputer'])-1];
        
        $punkty = explode("/",$str);
        switch($punkty[1]){
            case 'A':
                $_SESSION['punkty_komputer'] += 11;
                break;
            case 'K':
            case 'Q':
            case 'J':
                $_SESSION['punkty_komputer'] += 10;
                break;
            case '2':
                $_SESSION['punkty_komputer'] += 2;
                break;
            case '3':
                $_SESSION['punkty_komputer'] += 3;
                break;
            case '4':
                $_SESSION['punkty_komputer'] += 4;
                break;
            case '5':
                $_SESSION['punkty_komputer'] += 5;
                break;
            case '6':
                $_SESSION['punkty_komputer'] += 6;
                break;
            case '7':
                $_SESSION['punkty_komputer'] += 7;
                break;
            case '8':
                $_SESSION['punkty_komputer'] += 8;
                break;
            case '9':
                $_SESSION['punkty_komputer'] += 9;
                break;
            case '10':
                $_SESSION['punkty_komputer'] += 10;
                break;
        }
        // $_SESSION['punkty_gracz'] += ($punkty);
        if($_SESSION['punkty_komputer'] > 21){
            $_SESSION['wygrana'] = 'g';
        }
        else if($_SESSION['punkty_komputer'] == 21){
            $_SESSION['wygrana'] = 'k';
        }
        else if($_SESSION['punkty_komputer'] > 21 && $_SESSION['punkty_gracz'] > 21) $_SESSION['wygrana'] = 'r';
        
    }

    function dobierz_gracz(&$karty){
        if(!isset($_SESSION['wygrana'])){
            // foreach($_SESSION['komputer'] as $k){
            //     if($k == $nazwy[rand(0,3)]."/".$numery[rand(0,12)]){
            //         dobierz_gracz($nazwy,$numery);
            //     }
            // }
            // foreach($_SESSION['gracz'] as $g){
            //     if($g == $nazwy[rand(0,3)]."/".$numery[rand(0,12)]){
            //         dobierz_gracz($nazwy,$numery);
            //     }
            // }
            // $_SESSION['gracz'][count($_SESSION['gracz'])] = $nazwy[rand(0,3)]."/".$numery[rand(0,12)];
            // $str = $_SESSION['gracz'][count($_SESSION['gracz'])-1];
            // $punkty = explode("/",$str);
            
            $los = rand(0,count($_SESSION['karty'])-1);
            
            $_SESSION['gracz'][count($_SESSION['gracz'])] = $_SESSION['karty'][$los];
            
            $str = $_SESSION['gracz'][count($_SESSION['gracz'])-1];
            array_splice($_SESSION['karty'],$los,1);
            $punkty = explode("/",$str);
            switch($punkty[1]){
                case 'A':
                    if($_SESSION['punkty_gracz'] + 11 > 21) $_SESSION['punkty_gracz'] += 1;
                    else $_SESSION['punkty_gracz'] += 11;
                    break;
                case 'K':
                case 'Q':
                case 'J':
                    $_SESSION['punkty_gracz'] += 10;
                    break;
                case '2':
                    $_SESSION['punkty_gracz'] += 2;
                    break;
                case '3':
                    $_SESSION['punkty_gracz'] += 3;
                    break;
                case '4':
                    $_SESSION['punkty_gracz'] += 4;
                    break;
                case '5':
                    $_SESSION['punkty_gracz'] += 5;
                    break;
                case '6':
                    $_SESSION['punkty_gracz'] += 6;
                    break;
                case '7':
                    $_SESSION['punkty_gracz'] += 7;
                    break;
                case '8':
                    $_SESSION['punkty_gracz'] += 8;
                    break;
                case '9':
                    $_SESSION['punkty_gracz'] += 9;
                    break;
                case '10':
                    $_SESSION['punkty_gracz'] += 10;
                    break;
            }
            // $_SESSION['punkty_gracz'] += ($punkty);
            if($_SESSION['punkty_gracz'] > 21){
                $_SESSION['wygrana'] = 'k';
            }
            else if($_SESSION['punkty_gracz'] == 21){
                $_SESSION['wygrana'] = 'g';
            }
            else if ($_SESSION['punkty_gracz'] == 21 && $_SESSION['punkty_komputer'] == 21){
                $_SESSION['wygrana'] = 'r';
            }
            else if($_SESSION['punkty_komputer'] > 21 && $_SESSION['punkty_gracz'] > 21) $_SESSION['wygrana'] = 'r';

        }
        
        
    }

    function odpal_ai($karty){
        if($_SESSION['punkty_komputer'] <= 16){
            dobierz_komputer($karty);
        }
        else if($_SESSION['punkty_komputer'] > 16 && $_SESSION['komputer'] < 21){
            $liczba = rand(1,100);
            if($liczba === 12) dobierz_komputer($karty);
        }
        else if($_SESSION['punkty_komputer'] < 21){
            $_SESSION['komputer_pass'] = true;
        }
        if($_SESSION["komputer_pass"] == true && isset($_GET['pass'])){
            if($_SESSION['punkty_gracz'] > $_SESSION['punkty_komputer']){
                $_SESSION['wygrana'] = 'g';
            }
            else if ($_SESSION['punkty_gracz'] == $_SESSION['punkty_komputer']){
                $_SESSION['wygrana'] = 'r';
            }
            else $_SESSION['wygrana'] = 'k';
        }

    }

    function pokaz_karty($kto){
        // foreach($kto as $k){
        //     $k = explode("/",$kto[$]);
        //     echo "<img src='karty/".$k[0].$k[1].".png' alt='".$k[0].$k[1]."'>\n";
        // }
        for($i=0;$i<count($kto);$i++){
            $k = explode("/",$kto[$i]);
            // echo $k[0]."<br>";
            // echo $k[1]."<br>";
            echo "<img src='karty/".$k[0].$k[1].".png' alt='".$k[0].$k[1]."'>\n";
        }
    }

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlackJack Online</title>
    
    <style>
        html,body{
    background-image: url('stol.jpg');
    background-position: center top;
    background-size: 100% auto;
    color: rgb(200, 200, 200);
    font-size: larger;
    font-weight: bold;
}

main{
    max-width: 50%;
    text-align: center;
    background-color: darkgreen;
    height:100%;
    padding-top: 5%;
    margin-left: auto;
    margin-right: auto;
    /* position: absolute; */
    /* top: 50%; */
    /* transform: translate(50%,-50%); */
    border: 1rem solid rgb(3, 72, 3);
    box-shadow: 0px 20px 20px 20px black;
    /* display: grid; */
    display: flex;
    overflow: auto;
}



#karty_gracza img, #karty_komputera img{
    max-width: 20%;
    max-height: 20%;
    float: left;
}

#zwyciezca{
    width: 100%;
    clear: both;
    background-color: darkred;
    text-align: center;
    /* padding: 3% 0%; */
    padding-top: -3%;
    margin-bottom: 3%;
}

#karty_komputera, #karty_gracza{
    width: 100%;
}



input{
    background-color: darkolivegreen;
    min-width: 15rem;
    height: 3rem;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    color: rgb(212, 212, 212);
    font-size: large;
    font-weight: bold;
    border-radius: 20px;
}

input:hover{
    background-color: darkseagreen;
    color: black;
}

input:disabled{
    background-color: darkgrey;
    color: whitesmoke;
}

#karty_gracza{
    clear: both;
}
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <form method="GET">
            <?php
                if(isset($_SESSION['wygrana'])){
                    echo "<div id=\"zwyciezca\">";
                    if($_SESSION['wygrana'] == 'k'){
                        echo "<p>Komputer wygrał</p>";
                    }
                    else if($_SESSION['wygrana'] == 'r') echo "<p>Remis</p>";
                    else echo "\n<p>Gracz wygrał</p>";
                    echo "</div>";
                }
            ?>
            <input type="submit" name="zakoncz" value="Zakończ"> 
            <?php
            echo "<p>Twoje punkty: ".$_SESSION['punkty_gracz']."</p>\n";
            echo "<p>Punkty komputera: ".$_SESSION['punkty_komputer']."</p>\n";

            
            echo "<div id='menu'>\n";
            if(isset($_SESSION['wygrana'])){
                echo "<input type=\"submit\" name=\"dobierz\" value=\"Dobierz Karte\" disabled>\n";
                echo "<input type=\"submit\" name=\"pass\" value=\"Pass\" disabled> \n";
            }
            else{
                echo "<input type=\"submit\" name=\"dobierz\" value=\"Dobierz Karte\">\n";
                echo "<input type=\"submit\" name=\"pass\" value=\"Pass\">\n";
            }
            echo "</div>";

            

            //kąkuter
            echo "<div id=\"karty_komputera\">\n";
            echo "<p>Karty kąkutera:</p>";
            pokaz_karty($_SESSION['komputer']);
            echo "</div>";

            // echo "<br>";

            //gracz
            echo "<div id=\"karty_gracza\">\n";
            echo "<p>Twoje karty: </p>";
            pokaz_karty($_SESSION['gracz']);
            echo "</div>";
            

            ?>
        </form>
    </main>
</body>
</html>