<?php
// recupération des page regex.php et constante.php
require_once __DIR__ . '/../config/regex.php';
require_once __DIR__ . '/../config/constant.php';

// $currentdate = new DateTime();
// $currentdate -> format('Y-m-d');
$dateNow = date('Y-m-d');
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $errors['email'] = 'Veuillez entrer un email';
    } else {
        $isOk = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$isOk) {
            $errors['email'] = 'Veuillez entrer un email valide';
        }
    }
    // récuperation du mot de passe nettoyage et validation
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_DEFAULT);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_DEFAULT);

    if (empty($password1)) {
        $errors['password1'] = 'Veuillez entrer un mot de passe';
    } elseif ($password1 !== $password2) {
        $errors['password1'] = 'Veuillez entrer des mots de passe identique';
    } else {
        $isOk = filter_var($password1, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . REGEX_PASSWORD . '/']]);
        if (!$isOk) {
            $errors['password1'] = 'Veuillez entrer un mot de passe valide';
        }
        $password = password_hash($password1, PASSWORD_BCRYPT);
    }
    // récuperation du nom de famille nettoyage et validation
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    if (empty($lastname)) {
        $errors['lastname'] = 'Veuillez entrer un nom de famille ';
    } else {
        $isOk = filter_var($lastname, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . REGEX_NAME . '/']]);
        if (!$isOk) {
            $errors['lastname'] = 'Veuillez entrer un nom de famille valide';
        }
    }
    // récuperation du code postale  nettoyage et validation
    $zipCode = filter_input(INPUT_POST, 'zipCode', FILTER_SANITIZE_NUMBER_INT);
    if (empty($zipCode)) {
        $errors['zipCode'] = 'Veuillez entrer un code postal';
    } else {
        $isOk = filter_var($zipCode, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . REGEX_POSTAL . '/']]);
        if (!$isOk) {
            $errors['zipCode'] = 'Veuillez entrer un code postale valide';
        }
    }
    // récuperation du pays de naissance nettoyage et validation
    $nativeCountry = filter_input(INPUT_POST, 'nativeCountry');
    if (!in_array($nativeCountry, NATIVECOUNTRY)) {
        //ou if (in_array($nativeCountry,$nativeCountryList) == false){}
        // ou if (!array_key_exists($nativeCountry, NATIVECOUNTRY)) {
        $errors['nativeCountry'] = 'Veuillez entrer un pays de naissance valide';
    }
    // récuperation de la civilité nettoyage et validation
    $civility = filter_input(INPUT_POST, 'civility', FILTER_SANITIZE_NUMBER_INT);
    if (empty($civility)) {
        if ($civility != 1 && $civility != 2) {
            $errors['$civility'] = 'Veuillez entrer un genre valide';
        }
    }
    // récuperation de la date anniversaire nettoyage et validation
    $dateOfBirthday = filter_input(INPUT_POST, 'dateOfBirthday', FILTER_SANITIZE_NUMBER_INT);
    if (empty($dateOfBirthday)) {
        $errors['dateOfBirthday'] = 'Veuillez entrer une date de naissance';
    } else {
        $isOk = filter_var($dateOfBirthday, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . REGEX_BIRTHDAY . '/']]);
        if (!$isOk) {
            $errors['dateOfBirthday'] = 'Veuillez entrer une date de naissance valide';
        }
    }
    // récuperation de la civilité nettoyage et validation
    $urlLink = filter_input(INPUT_POST, 'urlLink', FILTER_SANITIZE_URL);
    if (empty($urlLink)) {
        $errors['urllinked'] = 'Veuillez entrer une url';
    } else {
        $isOk = filter_var($urlLink, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . REGEX_URL . '/']]);
        if (!$isOk) {
            $errors['urlLink'] = 'Veuillez entrer une url valide';
        }
    }
    //récuperation de la date anniversaire nettoyage et validation
    $langage = filter_input(INPUT_POST, 'langage', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    foreach ($langage as $key => $value) {
        if (!array_key_exists($value, LANGAGES)) {
            $errors['langage'] = 'Veuillez entrer un language valide';
        }
    }
    //récuperation et nettoyage du message
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!empty($message)) {
        if (strlen($message) > 500) {
            $errors['message'] = 'Veuillez entrer un message de moins de 500 caractéres';
        }
    }
    //récuperation du ficher recu nettoyage et validation
    try {
        $uploadfiles = ($_FILES['uploadfiles']);
        if (empty($uploadfiles)) {
            throw new Exception("Veuillez entrer un fichier", 1);
        }
        if ($uploadfiles['error'] != 0) {
            throw new Exception("Fichier non envoyé", 2);
        }
        if (!in_array($uploadfiles['type'], EXTENSION)) {
            throw new Exception("Veuillez entrer un fichier valide ( soit .png, .jpg, .jpeg, .gif, .pdf)", 3);
        }
        if ($uploadfiles['size'] > FILESIZE) {
            $errors['uploadfiles'] = 'Veuillez entrer un fichier avec une taille inferieur';
        }
        $extension = pathinfo($uploadfiles['name'], PATHINFO_EXTENSION);
        $newnamefile = uniqid('pp_') . '.' . $extension;
        $from = $uploadfiles['tmp_name'];
        $to = './public/uploads/users/' . $newnamefile;
        move_uploaded_file($from, $to);

    } catch (\Throwable $th) {
        $errors['uploadfiles'] = $th->getMessage();
    }
    // var_dump($errors['uploadfiles']);
}

include __DIR__ . '/../views/templates/header.php';
include __DIR__ . '/../views/userSignUp.php';
include __DIR__ . '/../views/templates/footer.php';