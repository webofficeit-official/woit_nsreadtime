<?php

$EM_CONF[$_EXTKEY] = [
    'title' => '[WIT] News Read Time',
    'description' => 'This extension is useful for displaying read time in  news detail view.',
    'category' => 'services',
    'author' => 'Team WebofficeIT, Rahul R S',
    'author_email' => 'info@webofficeit.com',
    'author_company' => 'Weboffice Infotech India Pvt. Ltd.',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
            'news' => '9.4.0-11.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];