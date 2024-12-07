<?php
return [
    'secret' => env('JWT_SECRET', 'your-default-secret'),
    'ttl' => 60,
    'algo' => 'HS256',
];
