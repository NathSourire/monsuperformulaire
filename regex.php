<?php  
define('REGEX_NAME', "^[A-Za-zéèêëïîôöùûüçàáâäæœÉÈÊËÎÏÔÖÙÛÜÇÀÁÂÄÆŒ'-]+$");
define('REGEX_POSTAL', "(?:0[1-9]|[1-8]\d|9[0-8])\d{3}");
define('REGEX_PASSWORD', "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$");
define('REGEX_BIRTHDAY', "^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$");
define('REGEX_URL', "^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)")
?>