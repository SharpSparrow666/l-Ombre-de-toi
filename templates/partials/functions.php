<?php

// Ce fichier contient toutes les fonctions nécessaires pour le site. Il doit être inclut sur toutes les pages.

// Pour que toutes les pages puissent travailler avec les variables en session
session_start();


// Fonction permettant de vérifier un captcha Google
function recaptchaValid($code, $ip = null)
{
    if(empty($code)) {
        return false;
    }
    $params = [
        'secret'    => '6Lc9-cElAAAAAJ5lcss5ZDFT_Mi3ukFNcprR_7Bb',  // Clé privée Google Recaptcha
        'response'  => $code
    ];
    if($ip){
        $params['remoteip'] = $ip;
    }
    $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
    if(function_exists('curl_version')){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
    }else{
        $response = file_get_contents($url);
    }
    if(empty($response) || is_null($response)){
        return false;
    }
    $json = json_decode($response);
    return $json->success;
}

// Fonction permettant de retourner une connexion à la bdd
function connectDb(){

    try{
        $bdd = new PDO('mysql:host=localhost;dbname=php_final_project;charset=utf8', 'root', '');
    } catch(Exception $e){
        die('Problème avec la base de données : ' . $e->getMessage());
    }

    return $bdd;

}

// Fonction permettant de tester si l'utilisateur est connecté (true si oui, sinon false)
function isConnected(){
    return isset($_SESSION['user']);
}


// Fonction permettant de bloquer une page si l'utilisateur est déjà connecté
function mustBeNotConnected(){

    // Si $_SESSION['user'] existe, alors l'utilisateur est connecté
    if(isConnected()){
        header('HTTP/1.1 403 Forbidden');
        require 'core/layouts/alreadyConnected.php';
        die();  // Empêche le reste de la page initiale de charger
    }

}

// Fonction permettant de bloquer une page si l'utilisateur n'est pas connecté
function mustBeConnected(){

    // Si $_SESSION['user'] n'existe pas, alors l'utilisateur n'est pas connecté
    if(!isConnected()){
        header('HTTP/1.1 403 Forbidden');
        require 'core/layouts/notConnected.php';
        die();  // Empêche le reste de la page initiale de charger
    }

}

// Fonction qui permettra d'afficher la classe CSS 'active' sur un lien du menu uniquement si $pageToTest contient le nom de la page actuelle
function setActiveIfPageIs($pageToTest){

    // On récupère le nom de la page actuelle via la fonction basename ( https://www.php.net/manual/fr/function.basename.php )
    $currentPage = basename($_SERVER['PHP_SELF']);

    if($currentPage == $pageToTest){
        echo ' active';
    }

}

// Liste des pays d'origine autorisés pour les fruits (sert à générer la liste de sélection et son bloc de verification)
// On crée l'array ici car plusieurs pages ont besoin de cet array
$fruitCountries = [
    'fr' => 'france',
    'de' => 'allemagne',
    'es' => 'espagne',
    'jp' => 'japon',
];