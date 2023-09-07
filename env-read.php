<?php
$envFilePath = __DIR__ . '/.env';

if (file_exists($envFilePath)) {
    // Wczytaj zawartość pliku .env
    $envContent = file_get_contents($envFilePath);

    // Podziel zawartość pliku na linie
    $envLines = explode("\n", $envContent);

    foreach ($envLines as $line) {
        // Podziel linię na klucz i wartość
        list($key, $value) = explode('=', $line, 2);

        // Usuń białe znaki z klucza i wartości
        $key = trim($key);
        $value = trim($value);

        // Ustaw zmienną środowiskową
        putenv("$key=$value");
    }
}
?>