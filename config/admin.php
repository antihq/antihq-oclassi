<?php

return [

    'emails' => array_filter(
        array_map('trim', explode(',', (string) env('APP_ADMIN_EMAILS', '')))
    ),

];
