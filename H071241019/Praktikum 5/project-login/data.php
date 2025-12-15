<?php

$users = [
    [
        'email' => 'admin@gmail.com',
        'username' => 'adminxxx',
        'name' => 'Admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
    ],
    [
        'email' => 'mima@gmail.com',
        'username' => 'mima_manis',
        'name' => 'Mima Jeon',
        'password' => password_hash('mima123', PASSWORD_DEFAULT),
        'gender' => 'Female',
        'faculty' => 'MIPA',
        'batch' => '2023',
    ],
    [
        'email' => 'leia@gmail.com',
        'username' => 'leia345',
        'name' => 'Leia',
        'password' => password_hash('Leia123', PASSWORD_DEFAULT),
        'gender' => 'Female',
        'faculty' => 'Hukum',
        'batch' => '2023',
    ],
    [
        'email' => 'skywalker@gmail.com',
        'username' => 'skywalker59',
        'name' => 'Skywalker',
        'password' => password_hash('skywalker123', PASSWORD_DEFAULT),
        'gender' => 'Female',
        'faculty' => 'Keperawatan',
        'batch' => '2021',
    ],
    [
        'email' => 'gracieabrams@gmail.com',
        'username' => 'gracieabrams23',
        'name' => 'Gracie Abrams',
        'password' => password_hash('gracie123', PASSWORD_DEFAULT),
        'gender' => 'Female',
        'faculty' => 'Teknik',
        'batch' => '2020',
    ],
];

?>
