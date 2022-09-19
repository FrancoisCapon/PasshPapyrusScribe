<?php

require_once('PasshPapyrus.php');

$passhCommand = "./passh";

$passPhraseMySQLRoot = new PasshPhrase(
    $passhCommand,
    "mysql", // mysqluser = osuser = root no password required
    [
        "mysql> ", "select current_user();",
        "mysql> ", "show databases;",
    ]
);

$passPhraseSuRoot = new PasshPhrase(
    'echo; id ; echo;'.$passhCommand,
    "su root -c \"echo; id ; echo; $passPhraseMySQLRoot\"; echo; id; echo",
    [
        "Password:", "azerty",
    ]
);

$passPhraseMySQLPentesterLab = new PasshPhrase(
    $passhCommand,
    "mysql -u pentesterlab -p", // mysqluser = pentesterlab
    [
        "Enter password:", "pentesterlab", // password needed
        "mysql> ", "select current_user();",
        "mysql> ", "show databases;",
    ]
);


$passParagraphWebShell = new PassParagraph (
    [
        $passPhraseSuRoot,
        $passPhraseMySQLPentesterLab,
    ]
);

echo PHP_EOL . $passParagraphWebShell . PHP_EOL. PHP_EOL;

