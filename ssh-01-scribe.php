<?php

require_once('PasshPapyrus.php');

$passPhraseChangeRootPassword = new PasshPhrase(
    "/tmp/remote-passh",  // passh on 192.168.1.29
    "sudo -s passwd",
    [
        "Enter new UNIX password:", "azerty",
        "Retype new UNIX password:", "azerty"
    ]
);

$passPhraseDoIt = new PasshPhrase(
    "./passh",
    "ssh user@192.168.1.29 bash -c \"tty; $passPhraseChangeRootPassword\"",
    // passh must be not the first instruction!
    [
        "password:", "live",
    ],
    False // not exit controller
);

echo PHP_EOL . $passPhraseDoIt. PHP_EOL. PHP_EOL;

/*
$passPhraseSetPasswordForRoot = new PasshPhrase(
    'echo; id ; echo;'.$passhCommand,
    "su root -c \"echo; id ; echo; $passPhraseMySQLRoot\"; echo; id; echo",
    [
        "Password:", "azerty",
    ]
);

$passPhraseRemoteCommand = new PasshPhrase(
    $passhCommand,
    "mysql -u pentesterlab -p", // mysqluser = pentesterlab
    [
        "Enter password:", "pentesterlab", // password needed
        "mysql> ", "select current_user();",
        "mysql> ", "show databases;",
    ]
);
*/



// echo PHP_EOL . $passPhraseRemoteCommand. PHP_EOL. PHP_EOL;

