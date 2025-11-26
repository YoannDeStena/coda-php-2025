<?php

$uppercaseAllowed = $_POST["uppercase"] ?? false;
$lowercaseAllowed = $_POST["lowercase"] ?? false;
$digitsAllowed = $_POST["digits"] ?? false;
$symbolsAllowed = $_POST["symbols"] ?? false;
$uppercaseChecked = $uppercaseAllowed ? "checked" : "";
$lowercaseChecked = $lowercaseAllowed ? "checked" : "";
$digitsChecked = $digitsAllowed ? "checked" : "";
$symbolsChecked = $symbolsAllowed ? "checked" : "";

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

$options = generateSelectOptions();

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
        <h1>Générateur de mots de passe $digitsAllowed</h1>
        <div>
            Mot de passe.
        </div>
        <div>
            <select class="form-select" name="size">
                $options
            </select>
        </div>
        <div>
            <form method="POST" action="index.php">
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