#  :memo: Examples of usage of `PasshPapyrus` from a `Scribe`

## :a: Connection with SSH and Interaction in the terminal

### :one: Define

```php
<?php

require_once('PasshPapyrus.php');

$passPhraseChangeRootPassword = new PasshPhrase(
    "/tmp/remote-passh", // passh on 192.168.1.29
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

```

### :two: Write

```
$ php -f ssh-01-scribe.php

./passh -c 1 -P 'password:' -p 'live' ssh user@192.168.1.29 bash -c "tty; /tmp/remote-passh -C -c 1 -P 'Retype new UNIX password:' -p 'azerty' /tmp/remote-passh -c 1 -P 'Enter new UNIX password:' -p 'azerty' sudo -s passwd"

```
### :three: Use
```
$ ./passh -c 1 -P 'password:' -p 'live' ssh user@192.168.1.29 bash -c "tty; /tmp/remote-passh -C -c 1 -P 'Retype new UNIX password:' -p 'azerty' /tmp/remote-passh -c 1 -P 'Enter new UNIX password:' -p 'azerty' sudo -s passwd"
user@192.168.1.29's password: 
not a tty
Enter new UNIX password: 
Retype new UNIX password: 
passwd: password updated successfully
```

## :b: Interaction in a p0wny@shell with a `PasshPapyrus`

### :one: Define

```php
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
```

### :two: Write

```
$ php -f webshell-01.php

echo; id ; echo;./passh -C -c 1 -P 'Password:' -p 'azerty' su root -c "echo; id ; echo; ./passh -C -c 2 -P 'mysql> ' -p 'show databases;' ./passh -c 1 -P 'mysql> ' -p 'select current_user();' mysql"; echo; id; echo ; ./passh -C -c 2 -P 'mysql> ' -p 'show databases;' ./passh -c 1 -P 'mysql> ' -p 'select current_user();' ./passh -c 1 -P 'Enter password:' -p 'pentesterlab' mysql -u pentesterlab -p

```
### :three: Use

```
p0wny@shell:…/admin/uploads# file passh
passh: ELF 32-bit LSB executable, Intel 80386, version 1 (SYSV), dynamically linked (uses shared libs), for GNU/Linux 2.6.18, not stripped
p0wny@shell:…/admin/uploads# echo; id ; echo;./passh -C -c 1 -P 'Password:' -p 'azerty' su root -c "echo; id ; echo; ./passh -C -c 2 -P 'mysql> ' -p 'show databases;' ./passh -c 1 -P 'mysql> ' -p 'select current_user();' mysql"; echo; id; echo ; ./passh -C -c 2 -P 'mysql> ' -p 'show databases;' ./passh -c 1 -P 'mysql> ' -p 'select current_user();' ./passh -c 1 -P 'Enter password:' -p 'pentesterlab' mysql -u pentesterlab -p

uid=33(www-data) gid=33(www-data) groups=33(www-data)

Password:

uid=0(root) gid=0(root) groups=0(root)

Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 89
Server version: 5.1.63-0+squeeze1 (Debian)

Copyright (c) 2000, 2011, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> select current_user();
+----------------+
| current_user() |
+----------------+
| root@localhost |
+----------------+
1 row in set (0.00 sec)

mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| photoblog          |
+--------------------+
3 rows in set (0.00 sec)

mysql> !! still prompted for passwords after 2 tries

uid=33(www-data) gid=33(www-data) groups=33(www-data)

Enter password:
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 90
Server version: 5.1.63-0+squeeze1 (Debian)

Copyright (c) 2000, 2011, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> select current_user();
+------------------------+
| current_user()         |
+------------------------+
| pentesterlab@localhost |
+------------------------+
1 row in set (0.00 sec)

mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| photoblog          |
+--------------------+
2 rows in set (0.00 sec)

mysql> !! still prompted for passwords after 2 tries
```
## Links

- https://pentesterlab.com/exercises/from_sqli_to_shell/course
- https://github.com/clarkwang/passh
- https://github.com/flozz/p0wny-shell
