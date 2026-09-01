<?php

return [
    'enabled' => env('CRON_ENABLED', false),
    'interval' => env('CRON_INTERVAL', 'everyMinute'),
];
