<?php

$uppercaseAllowed = $_POST["uppercase"] ?? 0;
$lowercaseAllowed = $_POST["lowercase"] ?? 0;
$digitsAllowed = $_POST["digits"] ?? 0;
$symbolsAllowed = $_POST["symbols"] ?? 0;
$size = $_POST["size"] ?? 10;
$uppercaseChecked = $uppercaseAllowed ? "checked" : "";
$lowercaseChecked = $lowercaseAllowed ? "checked" : "";
$digitsChecked = $digitsAllowed ? "checked" : "";
$symbolsChecked = $symbolsAllowed ? "checked" : "";

function selectRandomChar(string $string): string {
    $index = random_int(0, strlen($string) - 1);
    return $string[$index];
}

function generatePassword(int $size, int $uppercase, int $lowercase, int $digits, int $symbols): string {
    $password = "";
    $eligibleChars = "";
    //On s'assure qu'un caractère de chaque type sélectionné est présent.
    if($uppercase) {
        $size--;
        $uppercaseChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $eligibleChars = $eligibleChars . $uppercaseChars;
        $password = $password . selectRandomChar($uppercaseChars);
    }
    if($lowercase) {
        $size--;
        $lowercaseChars = "abcdefghijklmnopqrstuvwxyz";
        $eligibleChars = $eligibleChars . $lowercaseChars;
        $password = $password . selectRandomChar($lowercaseChars);
    }
    if($digits) {
        $size--;
        $digitsChars = "0123456789";
        $eligibleChars = $eligibleChars . $digitsChars;
        $password = $password . selectRandomChar($digitsChars);
    }
    if($symbols) {
        $size--;
        $symbolsChars = "!@#$%^&*()";
        $eligibleChars = $eligibleChars . "!@#$%^&*()";
        $password = $password . selectRandomChar($symbolsChars);
    }

    if(strlen($eligibleChars) == 0)
        return "Erreur : Aucun caractère éligible sélectionné.";

    for($i = 0; $i < $size; $i++) {
        $password = $password . selectRandomChar($eligibleChars);
    }

    return $password;
}

function generateSelectOptions(int $size = 10): string
{
    // on initialise une variable html vide
    $html = "";

    // utilisation de la fonction range pour générer un tableau de valeurs
    $options = range(8, 42);

    // pour chaque nombre de 8 à 42
    foreach ($options as $value) {
        // si le nombre courant est celui sélectionné, on ajoute l'attribut selected à l'option
        $attribute = "";
        if ((int)$value == (int)$size) {
            $attribute = "selected";
        }

        // on crée une option avec l'attribut et la valeur'
        $html .= "<option $attribute value=\"$value\">$value</option>";
    }
    return $html;
}

$options = generateSelectOptions($size);
$password = generatePassword($size, $uppercaseAllowed, $lowercaseAllowed, $digitsAllowed, $symbolsAllowed);

$html = <<<HTML
<!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Générateur de mots de passe </title>
        <link type="text/css" rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Générateur de mots de passe</h1>
        <div>
            $password
        </div>
        <form method="POST" action="index.php">
            <div>
                <select class="form-select" name="size">
                    $options
                </select>
            </div>
            <div>
                <input type="checkbox" name="uppercase" value="1" $uppercaseChecked ><label>Majuscules autorisées (A-Z)</label><br>
                <input type="checkbox" name="lowercase" value="1" $lowercaseChecked><label>Minuscules autorisées (a-z)</label><br>
                <input type="checkbox" name="digits" value="1" $digitsChecked><label>Chiffres autorisés (1-9)</label><br>
                <input type="checkbox" name="symbols" value="1" $symbolsChecked><label>Symboles autorisées (!@#$%^&*())</label>
                <button type="submit">Générer</button>
            </form>
        </div>
    </body>
    </html>
HTML;

echo $html;