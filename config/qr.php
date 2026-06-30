<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QR Code Destination URLs
    |--------------------------------------------------------------------------
    | Set these in your .env file. Each QR type redirects to its destination
    | after the scan has been recorded in the database.
    */
    'destinations' => [
        'web'     => env('QR_DEST_WEB',     'https://yourwebsite.com'),
        'android' => env('QR_DEST_ANDROID', 'https://play.google.com/store/apps/details?id=com.yourapp'),
        'ios'     => env('QR_DEST_IOS',     'https://apps.apple.com/app/yourapp/id123456789'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GPS / Location Capture
    |--------------------------------------------------------------------------
    | When true, QR scans first go through a location-bridge page that asks
    | the user's browser for GPS permission before redirecting.
    | When false, users are redirected immediately (no GPS prompt).
    */
    'capture_gps' => env('QR_CAPTURE_GPS', true),

    /*
    |--------------------------------------------------------------------------
    | IP Geolocation API
    |--------------------------------------------------------------------------
    | Uses ip-api.com (free, no API key required, 45 req/min).
    | {ip} will be replaced with the visitor's IP address at runtime.
    */
    'geo_api_url' => 'http://ip-api.com/json/{ip}?fields=country,city,lat,lon,status',

    /*
    |--------------------------------------------------------------------------
    | Geo API Timeout (seconds)
    |--------------------------------------------------------------------------
    | How long to wait for the geolocation response before giving up.
    | Keep this low to avoid slowing down the redirect.
    */
    'geo_timeout' => 2,

];
