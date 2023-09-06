<?php
require './contante.php';
require './regex.php';
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

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="./public/assets/js/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/assets/css/style.css">
    <title>PHP exo 1</title>
</head>

<body>
    <header class="container-fluid">
        <div class="row">
            <img class="imgLogo col-3" src="./public/assets/img/PHP-logo.png" alt="Logo PHP">
            <h1 class="col-7">TP 1</h1>
            <p>TP 1 Devoir
                Faire un formulaire d'inscription permettant à un utilisateur de saisir les informations suivantes :
                E-mail
                Mot de passe
                Civilité (Mr, Mme)
                Nom
                Date de naissance
                Pays de naissance (France, Belgique, Suisse, Luxembourg, Allemagne, Italie, Espagne, Portugal)
                Code postal
                Photo de profil
                Url compte linked
                Quel langages web connaissez-vous? (HTML/CSS, PHP, Javascript, Python, Autres)
                Racontez une expérience avec la programmation et/ou l'informatique que vous auriez pu avoir.
                Les données saisies par l'utilisateurs devront être contrôlées et validées côté Front, mais également
                côte Back
                Si toutes les données sont valides, alors, afficher un récapitulatif à l'utilisateur.</p>
        </div>
    </header>
    <main>
        <h2>Mon super formulaire</h2>
        <form id="form" enctype="multipart/form-data" method="post" novalidate>
            <fieldset class="container-fluid">
                <div class="row results ms-5 my-5 row-gap-4">
                    <div class="">
                        <label class="form-label" for="email">E-mail *</label>
                        <input class="form-control" type="email" id="email" name="email" value="" required>
                        <p class="red">
                            <?= $errors['email'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="passWord1">Mot de passe *</label>
                        <input class="form-control" id="passWord1" type="password" name="password1" pattern="<?= REGEX_PASSWORD ?>" required><br>
                        <p class="red">
                            <?= $errors['password1'] ?? '' ?>
                        </p>
                        <label class="form-label" for="passWord2">Vérification du mot de pass *</label>
                        <input class="form-control my-3" id="passWord2" type="password" name="password2" pattern="<?= REGEX_PASSWORD ?>" required>
                        <p>Le mot de passe doit contenir au moins 8 caractères, une lettre en majuscule, une lettre en minuscule, un chiffre et un caractère spécial.<br>
                            Les deux doivent être identique.</p>
                        <p class="red" id="passwordMessage"></p>
                    </div>
                    <div>
                        <label class="form-label" for="civility1">Civilité :</label><br>
                        <input class="ms-5" type="radio" value="1" id="civility1" name="civility">Mr<br>
                        <label class="form-label" for="civility2">Civilité :</label><br>
                        <input class="ms-5" type="radio" value="2" id="civility2" name="civility">Mme
                        <p class="red">
                            <?= $errors['civility'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="lastnames">Nom *</label>
                        <input class="form-control" type="text" id="lastnames" name="lastname" pattern="<?= REGEX_NAME ?>" required>
                        <p class="red">
                            <?= $errors['lastname'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="birthdaydate">Date de naissance *</label>
                        <input class="form-control" type="date" id="birthdaydate" name="dateOfBirthday" max="<?= $dateNow ?>" required>
                        <p class="red">
                            <?= $errors['dateOfBirthday'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="nativecountries">Pays de naissance</label>
                        <select class="form-select" id="nativecountries" name="nativeCountry">
                            <option selected disabled value="">Pays de naissance</option>
                            <?php
                            foreach (NATIVECOUNTRY as $key => $value) { ?>
                                <option>
                                    <?= $value ?>
                                </option>
                            <?php } ?>
                        </select>
                        <p class="red">
                            <?= $errors['nativeCountry'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="zipcode">Code postal</label>
                        <input class="form-control" type="text" id="zipcode" name="zipCode" pattern="<?= REGEX_POSTAL ?>">
                        <p class="red">
                            <?= $errors['zipCode'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="profilpicture">Photo de profil</label>
                        <div class="border border-3 pictureProfil ms-5 "></div>
                        <input class="form-control my-3" type="file" id="profilpicture" name="uploadfiles" accept=".png, .jpeg, .jpg, .gif">
                        <p class="red">
                            <?= $errors['file'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label" for="urllinked">Url compte Linked</label>
                        <input class="form-control" id="urllinked" type="url" name="urlLink" pattern=" <?= REGEX_URL ?> ">
                        <p class="red">
                            <?= $errors['urlLink'] ?? '' ?>
                        </p>
                    </div>
                    <div>
                        <label class="form-label">Langages web :</label><br>
                        <?php
                        foreach (LANGAGES as $key => $value) { ?>
                            <label for="langage_<?= $key ?>"></label>
                            <input class="my-3 ms-5" id="langage_<?= $key ?>" type="checkbox" name="langage[]" value="<?= $key ?>">
                            <?= $value ?><br>
                        <?php } ?>
                    </div>
                    <div>
                        <label class="form-label" for="message">Votre experience</label> <br>
                        <textarea name="message" id="message" cols="50" rows="10" maxlength="500" placeholder="Racontez une expérience avec la programmation et/ou l'informatique que vous auriez pu avoir."></textarea>
                    </div>
                </div>
            </fieldset>
            <div>
                <button type="submit" class="btn btn-outline-primary ms-5">Envoie !</button>
            </div>
        </form>
        <p class="ms-5">* Champ obligatoire</p>
    </main>
    <script>
        // pour s'en servir dans le js
        const regexName = <?= REGEX_NAME ?>;
        const regexBirthday = <?= REGEX_BIRTHDAY ?>;
        const regexPassword = <?= REGEX_PASSWORD ?>;
        const regexPostal = <?= REGEX_POSTAL ?>;
        const regexUrl = <?= REGEX_URL ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>