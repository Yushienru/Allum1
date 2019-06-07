<?php

$pyra = array(1=>1);
$pyra_vide = array(1=>0);

function checkWin($player) {
    global $pyra;

    $i = 0;
    foreach($pyra as $key => $ligne) {
        if($ligne == 0) {
            $i++;
        }
    }

    if($i == $key) {
        if($player == 0) {
            echo "T'as perdu GROOOOOS !!! \n";
            exit (2);
        }
        else {
            echo "T'as gagné GROOOOOOS !!! \n";
            exit (1);
        }
    }
}

function checkLine($line, $limit, $ligne) {
    global $pyra;
    global $pyra_vide;

    echo "Combien souhaitez-vous retirer d'allumettes ? ($limit): ";
    $jeux = readLine();
    if($jeux > 0 && $jeux <= $limit && $jeux <= $pyra[$ligne]) {
        $pyra[$ligne] = $pyra[$ligne] - $jeux;
        $pyra_vide[$ligne] += $jeux;
        echo "Vous jouez à la ligne $ligne et retirez $jeux allumettes \n";
    }
    elseif($jeux == "stop") {
        exit;
    }
    else {
        echo " Entrée incorrecte, veuillez entrer un nombre entre 1 et $limit ou stop pour arrêter de jouer: \n";
        checkLine($line, $limit, $ligne);
    }
}

function playerHuman($line, $limit) {
    global $pyra;
    echo "Quelle ligne voulez-vous jouer ? ($line max): \n";
    $ligne = readline();
    if ($ligne != 0 && $ligne <= $line && isset($pyra[$ligne]) && $pyra[$ligne] > 0) {
        checkLine($line, $limit, $ligne);
    }
    elseif ($ligne == "stop") {
        exit;
    }
    else {
        echo "Entrée incorrecte, veuillez entrer un nombre entre 1 et $line ou stop pour arrêter de jouer:  \n";
        playerHuman($line, $limit);
    }
}

function checkPNJLine($line, $limit, $ligne) {
    global $pyra;
    global $pyra_vide;

    $jeux = rand(1, $limit);
    if ($jeux <= $pyra[$ligne]) {
        $pyra[$ligne] = $pyra[$ligne] - $jeux;
        $pyra_vide[$ligne] += $jeux;
        echo "Le PNJ joue la ligne $ligne et retire $jeux allumettes \n";
    }
    else {
        checkPNJLine($line, $limit, $ligne);
    }
}

function playerPNJ($line, $limit) {
    global $pyra;

    $ligne = rand(1, $line);
    if(isset($pyra[$ligne]) && $pyra[$ligne] > 0) {
        checkPNJLine($line, $limit, $ligne);
    }
    else {
        playerPNJ($line, $limit);
    }
}

function play($line, $limit) {
    static $player = 0;
    if ($player == 0) {
        playerHuman($line, $limit);
        checkWin($player);
        $player = 1;
    }
    else {
        playerPNJ($line, $limit);
        checkWin($player);
        $player = 0;
    }
    printTurn($line, $limit);
}

function lineSE($large) {
   
    for ($i = 0; $i < $large; $i++) {
        echo "*";
    }
    echo "\n";
}

// Construction du tableau de jeu

function printTurn($line, $limit) {
    global $pyra;
    global $pyra_vide;

    lineSE ($line * 2 + 1);
    for ($i = 1; $i <= $line; $i++) {
        echo "*";
        for ($j = 0; $j < $line - $i; $j++) {
            echo " ";
        }

        for ($j = 0; $j < $pyra[$i]; $j++) {
            echo "|";
        }

        for ($j = 0; $j < $line - $i + $pyra_vide[$i]; $j++) {
            echo " ";
        }
        echo "* \n";
    }
    lineSE($line * 2 + 1);
    play($line, $limit);
}

function setPyra($line) {
    global $pyra;
    global $pyra_vide;
    $ii = 1;

    for ($i = 2; $i <= $line; $i++) {
        $pyra[$i] = $ii * 2 + 1;
        $pyra_vide[$i] = 0;
        $ii += 1;
    }
}

// Défini le nombre d'allumettes que l'on va pouvoir enlever

function defAllum($texte2) {
    echo $texte2;
    $limit = readline();
    if ($limit > 0 && $limit <= 25) {
        return $limit;
    }
    elseif($limit = "stop") {
        exit;
    }
    else {
        $texte2 = "Entrée incorrecte veuillez entrer un nombre entre 1 et 25 ou stop pour quitter le jeu: ";
        $limit = defAllum($texte2);
        return $limit;
    }

}



function start($texte) {

    echo $texte;
    $line = readline();

    if ($line > 0 && $line < 100) {
        $texte2 = "Combien voulez vous enlever d'allumettes au maximum ?: ";
        $limit = defAllum($texte2);

        setPyra($line);
        printTurn($line, $limit);
    }
    elseif ($line == "stop") {
        exit;
    }
    else {
        $texte = "Entrée incorrecte veuillez entrer un nombre entre 1 et 99: ";
        start($texte);
    }

}

$texte = "Combien voulez vous de lignes ? (entre 1 et 99): ";

start($texte);

