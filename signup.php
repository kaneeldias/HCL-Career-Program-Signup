<?php

    header('Access-Control-Allow-Origin: *');

    include_once "config.php";
    include_once "sheets.php";

    if (!isset($_POST['product']) ||
        !isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['school']) ||
        !isset($_POST['education']) ||
        !isset($_POST['expectations']) ||
        !isset($_POST['preference']) ||
        !isset($_POST['g-recaptcha-response'])){
        $output = json_encode(array('type' => 'fail', 'text' => "Incomplete form"));
        die($output);
    }

    if (!isset($_POST['entity'])){
        $_POST['entity'] = "Others";
    }

    if (!isset($_POST['track'])){
        $_POST['track'] = "";
    }

    if (!isset($_POST['phone'])){
        $_POST['phone'] = "";
    }


    $product = $_POST['product'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $school = $_POST['school'];
    $education = $_POST['education'];
    $expectations = $_POST['expectations'];
    $preference = $_POST['preference'];

    $entity = $_POST['entity'];
    $track = $_POST['track'];

    $gcaptcha = $_POST['g-recaptcha-response'];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $gcaptcha_private,
        'response' => $gcaptcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $curlConfig = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data
    );

    $ch = curl_init();
    curl_setopt_array($ch, $curlConfig);
    $response = curl_exec($ch);
    curl_close($ch);

    $jsonResponse = json_decode($response);

    /*
    if (!$jsonResponse || !$jsonResponse->success === true) {
        $output = json_encode(array('type' => 'fail', 'text' => "Captcha invalid"));
        die($output);
    }
    */

    $date = new DateTime("now", new DateTimeZone('Asia/Colombo') );
    $timestamp = $date->format('Y-m-d H:i:s');

    $res = append([[$timestamp, $first_name." ".$last_name, $email, $phone, $school, $education, $expectations, $preference, $track]], $entity);

    if ($res) {
        $output = json_encode(array('type' => 'success', 'text' => "Details successfully submitted."));
        die($output);
    } else{
        $output = json_encode(array('type' => 'fail', 'text' => "An unknown error occurred."));
        die($output);
    }



?>
