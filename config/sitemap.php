<?php

use GuzzleHttp\RequestOptions;

return [

    /*
     * These options will be passed to GuzzleHttp\Client when it is created.
     * For in-depth information on all options see the Guzzle docs:
     *
     * http://docs.guzzlephp.org/en/stable/request-options.html
     */
    'guzzle_options' => [

        /*
         * Whether or not cookies are used in a request.
         */
        RequestOptions::COOKIES => true,

        /*
         * The number of seconds to wait while trying to connect to a server.
         * Use 0 to wait indefinitely.
         */
        RequestOptions::CONNECT_TIMEOUT => 10,

        /*
         * The timeout of the request in seconds. Use 0 to wait indefinitely.
         */
        RequestOptions::TIMEOUT => 10,

        /*
         * Describes the redirect behavior of a request.
         */
        RequestOptions::ALLOW_REDIRECTS => false,
    ],

];
