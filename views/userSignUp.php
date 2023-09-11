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
                        <input class="ms-5" type="radio" value="1" id="civility1" name="civility">Mr
                        <label class="form-label" for="civility2"></label><br>
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

