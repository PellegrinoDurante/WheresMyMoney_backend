<?php
return [
    'driver_uri' => env('REMOTE_WEB_DRIVER_URI', 'http://selenium:4444/wd/hub'),
    'chrome' => [
        'arguments' => [
            '--window-size=1920,1080',
            '--disable-gpu',
            '--headless',
            '--disable-dev-shm-usage', # https://stackoverflow.com/questions/53902507/unknown-error-session-deleted-because-of-page-crash-from-unknown-error-cannot
        ]
    ]
];
