<?php

$data = [
    'firstname' => 'Alice',
    'email'=>'alice@email.com',
    'age'=> 25
];

// tableau => format json
$json  = json_encode($data, JSON_PRETTY_PRINT);

echo gettype($json); // string

echo $json;
// {
//     "firstname": "Alice",
//     "email": "alice@email.com",
//     "age": 25
// }