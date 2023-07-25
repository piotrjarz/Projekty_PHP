<?php
    session_start();

    if(isset($_POST['width']) && isset($_POST['height']) && isset($_POST['bombs']))
    {
            if(($_POST['bombs']) >= intval($_POST['width']) * intval($_POST['height']))
                $_POST['bombs'] = intval($_POST['height']) * intval($_POST['width']/3);
            $_SESSION['bombs'] = $_POST['bombs'];
            $_SESSION['width'] = $_POST['width'];
            $_SESSION['height'] = $_POST['height'];
            $_SESSION['play'] = $_POST['play'];
            header('location: index.php');
        
        // session_destroy();
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saper online</title>
    <style>
        body, html{
            background-color: rgb(49, 48, 48);
            padding: 0%;
            margin: 0%;
            font-family: 'Comic Sans Ms','Courier New', Courier, monospace;
        }

        header{
            color: white;
            text-align: center;
            font-size: xx-large;
        }

        form{
            padding: 0% 33%;
            color: white;
            font-size: larger;
        }

        form p{
            padding-bottom: 0%;
            font-size: xx-large;
        }

        input[type='number']{
            background-color: antiquewhite;
            border: 1px solid antiquewhite;
            border-radius: 10px;
            padding: 2% 5%;
            margin: 1%;
        }

        input[type='submit']{
            background-color: beige;
            padding: 2% 5%;
            border-radius: 20px;
            font-size: x-large;
        }

        input[type='submit']:hover{
            background-color: rgb(226, 226, 176);
        }

        footer{
            color: darkred;
            text-align: center;
            padding: 1% 0%;
            font-size: large;
            border-top: 5px solid darkred;
            border-radius: 50px;
            /* position: sticky; */
        }

        article, h2{
            color: antiquewhite;
            font-size: larger;
            padding: 2% 10%;
        }
        h2{
            padding: 0% 10%;
            font-size: x-large;
        }
    </style>
</head>
<body>
    <header>
        <h1>Saper online</h1>
    </header>
    <form action="graj.php" method="POST">
        <p>Podaj wielkość pól:</p>
        Szerokość: <input class="dane" name="width" type="number" min="4" max="15"><br>
        Wysokość: <input class="dane" name="height" type="number" min="4" max="15"><br>
        Ilość bomb na planszy: <input class="dane" name="bombs" type="number" min="1" max="75"><br><br>
        <input name="play" type="submit" value="Zagraj">
    </form>
    <section>
        <h2>Jak grać?</h2>
        <article>
            <h3>Zasady</h3>
            Saper polega na bezpiecznym przejściu przez pole minowe (planszę) oraz zdetonowaniu na nim wszystkich min. Po kliknięciu na dowolne, nieodkryte pole na planszy - o ile nie jest to mina -, gracz odkrywa pole. Mogą na nim znaleźć się następujące wartości:<br>
            <ul>
                <li><b>Puste pole</b> - oznacza, że pole nie graniczy z żadną bombą.</li>
                <li><b>Wartości od 1 do 8</b> - pole z daną wartością mówi nam, z iloma minami graniczy.</li>
            </ul>
            <br>
            Aby wygrać należy odkryć całą planszę oraz zdetonować wszystkie miny.
        </article>
        <article>
            <h3>Interface</h3>
            W skład interface'u wchodzą trzy przyciski:<br>
            <ul>
                <li><img src="face.png" alt="restart button, smiling face" width=30px height=30px> - restart planszy</li>
                <li><img src="flaga.png" alt="flag button, triangular flag" width=30px height=30px> - stawianie flag, które detonują miny</li>
                <li><img src="menu.png" alt="menu button, clickbait arrow" width=30px height=30px> - powrót do menu głównego (strona, na której się znajdujesz)</li>
            </ul>
        </article>
        <article>
            <h3>Jak naliczane są punkty?</h3>
            Punkty są naliczane w sposób następujący:
            <ul>
                <li><b>Odkrycie pustych pól</b> - 20pkt</li>
                <li><b>Odkrycie każdego innego pola (wliczając w to stawianie flag)</b> - 10pkt</li>

            </ul>
        </article>
    </section>
    <footer>
        <p>Autor: Piotr Jarzęmbski 2HT</p>
    </footer>
</body>
</html>

