<?php

return [
    'hosts' => [
        env('ELASTICSEARCH_HOSTS', 'http://elasticsearch:9200'),
    ],
    'driver' => 'elastic',
    'elasticsearch' => [
        'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', 'http://elasticsearch:9200')),
    ],
];
