<?php
error_reporting(E_ERROR | E_PARSE);
/*
    TODO: dodaj możliwość oznaczania danego pola jako bomba
*/

session_start();

if(!isset($_SESSION['width']) || isset($_GET['menu'])){
    session_destroy();
    header('location: graj.php');
}



function wylaczSesje(){
    unset($_SESSION['przegrana'],$_SESSION['punkty'],$_SESSION['maska_odkrycia'],$_SESSION['plansza'],$_SESSION['flaga'],$_SESSION['wolne_pola'],$_SESSION['ilosc_flag']);
}




if(!isset($_SESSION['punkty'])){
    $_SESSION['punkty'] = 0;
}







//! ustawianie flagi
if(isset($_GET['flaga']) && !isset($_SESSION['flaga'])){
    // echo "AAAAAAAAAA";
    $_SESSION['flaga'] = true;
}

else if(isset($_GET['flaga']) && $_SESSION['flaga'] == true){
    // echo "Ja bardzo lubię grappe grappe grappe grappe";
    unset($_SESSION['flaga']);
}

if(!isset($_SESSION['wolne_pola'])){
    $_SESSION['wolne_pola'] = intval($_SESSION['width']) * intval($_SESSION['height']);
    // echo "<h1>".$_SESSION['wolne_pola']."</h1>";
}



if(!isset($_SESSION['ilosc_flag'])){
    $_SESSION['ilosc_flag'] = $_SESSION['bombs'];
}

//! maska flagi - na razie niepotrzebne
if(!isset($_SESSION['maska_flagi'])){
    $maska_flagi[$x+2][$y+2];
    $_SESSION['maska_flagi'] = $maska_flagi;
}






if(isset($_SESSION['width'])){
    $x = $_SESSION['width'];
    $y = $_SESSION['height'];
    $ileBomb = $_SESSION['bombs'];
}



if(!isset($_SESSION['przegrana'])){
    $_SESSION['przegrana'] = FALSE;
}



if(isset($_GET['reset'])){
    wylaczSesje();
    header('Location: index.php');
}


//! maska odkryć planszy
if(!isset($_SESSION['maska_odkrycia'])){
    $maska_odkrycia[$x+2][$y+2];
    for($i=0;$i<$x+2;$i++){
        for($j=0;$j<$y+2;$j++){
            $maska_odkrycia[$i][$j] = FALSE;
        }
    }
    $_SESSION['maska_odkrycia'] = $maska_odkrycia;
}
$maska_odkrycia = $_SESSION['maska_odkrycia'];





if(!isset($_SESSION['plansza'])){

    $plansza[$x+2][$y+2];

    start($plansza,$x,$y,$ileBomb);

    $_SESSION['plansza'] = $plansza;

    

}

$plansza = $_SESSION['plansza'];



function postawFlage(&$p,&$sp,&$mf,&$wp,$x,$y){
    if($_SESSION['ilosc_flag'] > 0){
        $_SESSION['wolne_pola']--;
        $mf[$x][$y] = $p[$x][$y];
        $p[$x][$y] = 'F';
        $sp[$x][$y] = 'F';
        $_SESSION['ilosc_flag']--;
        $_SESSION['wolne_pola'] = sprawdzIleWolnych($maska_odkrycia,$x,$y);
    }
    else{
        unset($_SESSION['flaga']);
    };

}

if(isset($_GET['pole'])){

    $poleX = explode('_',$_GET['pole'])[0];
    $poleY = explode('_',$_GET['pole'])[1];

    //!A jednak działa :D
    if($_SESSION['flaga']){ 
        // echo "Ustawiam pole z wartością: ".$plansza[$poleX][$poleY]." na wartość 10<br>";
        // $plansza[$poleX][$poleY] = 10;
        // echo "Ustawiłem to pole: ".$plansza[$poleX][$poleY];
        postawFlage($plansza,$_SESSION['plansza'],$_SESSION['maska_flagi'],$_SESSION['wolne_pola'],$poleX,$poleY);
        // $_SESSION['wolne_pola']--;

        $maska_odkrycia[$poleX][$poleY] = true;
        $_SESSION['maska_odkrycia'] = $maska_odkrycia;

        // odkryjPola($plansza,$maska_odkrycia,$poleX,$poleY);
    }
    else if($plansza[$poleX][$poleY] == 0){
        odkryjPola($plansza,$maska_odkrycia,$poleX,$poleY);
    }
    else if($plansza[$poleX][$poleY] == 9){
        $_SESSION['przegrana'] = TRUE;
    }
    $maska_odkrycia[$poleX][$poleY] = TRUE;
    $_SESSION['maska_odkrycia'] = $maska_odkrycia;
    $_SESSION['wolne_pola']--;
    $_SESSION['wolne_pola'] = sprawdzIleWolnych($maska_odkrycia,$x,$y);
    
    // $_SESSION['wolne_pola']-=1;

    if($plansza[$poleX][$poleY] != 9)
        $_SESSION['punkty'] += 10;
}



function sprawdzIleWolnych($m,$x,$y){
    $ilosc_wolnych = 0;
    for($i=1;$i<=$x;$i++){
        for($j=1;$j<=$y;$j++){
            if($m[$i][$j] == false){
                $ilosc_wolnych++;
            }
        }
    }
    return $ilosc_wolnych;
}



function odkryjPola($p,&$m,$x,$y){
    
    for($i=$x-1;$i<=$x+1;$i++){

        for($j=$y-1;$j<=$y+1;$j++){

            if($p[$i][$j]==0&&$m[$i][$j]==false){
                $m[$i][$j]=true;
                $_SESSION['punkty'] += 10;
                $_SESSION['wolne_pola']--;
                odkryjPola($p, $m, $i, $j);
                $_SESSION['wolne_pola']--;

            }
            else{
                $_SESSION['wolne_pola']--;
                $m[$i][$j]=true;

            }

        }
    }
    $_SESSION['wolne_pola'] = sprawdzIleWolnych($maska_odkrycia,$x,$y);

}


function start(&$p,$x,$y,$ileBomb){
    for($i=0;$i<$x+2;$i++){
        for($j=0;$j<$y+2;$j++){
            if($i == 0 || $i == $x+1){
                $p[$i][$j] = 10;
            }
            else if($j == 0 || $j == $y+1){
                $p[$i][$j] = 10;
            }
            else{
                $p[$i][$j] = 0;
            }
        }
}

    for($i=0;$i<$ileBomb;$i++){

        do{

            $bombaX = rand(1,$x);

            $bombaY = rand(1,$y);

        }while($p[$bombaX][$bombaY] == 9);

        $p[$bombaX][$bombaY] = 9;

    }

    for($i=1;$i<=$x;$i++)
    {
        for($j=1;$j<=$y;$j++)
        {

            if($p[$i][$j]!=9){

                $bombySasiedzi=0;
                //wiersz niżej
                if($p[$i-1][$j-1]==9)
                    $bombySasiedzi++;

                if($p[$i-1][$j+1]==9)
                    $bombySasiedzi++;

                if($p[$i-1][$j]==9)
                    $bombySasiedzi++;

                
                //ten sam wiersz
                if($p[$i][$j-1]==9)
                    $bombySasiedzi++;

                if($p[$i][$j+1]==9)
                    $bombySasiedzi++;
                
                if($p[$i+1][$j-1]==9)
                    $bombySasiedzi++;


                if($p[$i+1][$j+1]==9)
                    $bombySasiedzi++;

                if($p[$i+1][$j]==9)
                    $bombySasiedzi++;
                $p[$i][$j] = $bombySasiedzi;

            }
        }
    }
}

function pokazPlansze($p,$m,$x,$y){
    if($_SESSION['wolne_pola'] == 0 && !$_SESSION['przegrana']){
        echo "<h1>Gratulacje! <br>Wygrałeś :D</h1><h3>Aby rozpocząć nową grę, naciśnij przycisk restartu!</h3>";
    }
    else{
        for($i=1;$i<=$x;$i++)
        {
            for($j=1;$j<=$y;$j++)
            {
                echo "<button name='pole' value='$i"."_"."$j'";//.$plansza[$i][$j]."&nbsp;";
                        
                if($m[$i][$j]==TRUE){
                    if($p[$i][$j]==0){
                        echo " class=\"puste\" ";
                        echo " disabled>";
                        echo "&nbsp;";
                    }
                    else if($p[$i][$j] == 9){
                        echo " class=\"bomba\" ";
                        echo " disabled>";
                        echo "*";
                        $_SESSION['przegrana'] = TRUE;
                    }

                    else if($p[$i][$j] == 10){
                        echo " class='flaga' disabled>10</button>";
                    }
                    else{
                        echo " disabled>";
                        echo $p[$i][$j];
                    }
                }
                    // else if($m[$i][$j] == FALSE && $mf[$i][$j] == TRUE){
                    //     echo ">🚩";
                    // }
                else{
                    echo ">";
                    echo "&nbsp";
                }
            }
    
                echo "</button>";
                echo "<br>\n";
                    
        }
        echo 'Aktualne punkty: '.$_SESSION['punkty'];
        // echo '<br>Wolne pola: '.$_SESSION['wolne_pola'];
        if($_SESSION['przegrana']){
            echo "<h1>Przegrałeś :(</h1><h3>Aby zagrać ponownie kliknij na planszę lub na przycisk restartu!</h3>";
            wylaczSesje();
        }
    }
}

        


        


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saper</title>
    <link rel="stylesheet" href="style.css">
    <style>
        button{
            width: 50px;
            height: 50px;
            background-color: #d8d3cd;
            border:none;
            border: 1px solid #c2bdb7;
            font-size: 1.1em;
        }

        #saper{
            text-align: center;
            padding: 5% 0%;
        }
    </style>
</head>

<body>
    <div id="saper">
        <form method="GET">
            <button class="przycisk" name="reset" value=1><img src="face.png" alt="reset button" width=30px height=30px></button>
            <button class="przycisk" name="flaga" value=1><img src="flaga.png" alt="flag button" width=30px height=30px></button>
            <button class="przycisk" name="menu" value=1><img src="menu.png" alt="menu button" width=30px height=30px></button>
            <br><br>
        <?php

            pokazPlansze($plansza,$maska_odkrycia,$x,$y);
        ?>
        <!-- <h1 id="info"></h1> -->
        </form>
    </div>
    



</body>

</html>