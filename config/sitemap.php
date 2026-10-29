<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Canonical Base URL
    |--------------------------------------------------------------------------
    |
    | The base canonical URL for sitemap generation. Defaults to the live
    | production domain https://www.directimported.com unless overridden
    | by CANONICAL_URL in .env.
    |
    */
    'canonical_url' => env('CANONICAL_URL', 'https://www.directimported.com'),

    /*
    |--------------------------------------------------------------------------
    | Include Sold Vehicle Listings
    |--------------------------------------------------------------------------
    |
    | When true, sold vehicles (which remain viewable with inquiry forms on
    | the available-vehicles page) are included in the sitemap. Drafts are
    | always excluded. Set to false to include only 'available' and 'under_offer'.
    |
    */
    'include_sold_vehicles' => env('SITEMAP_INCLUDE_SOLD_VEHICLES', true),
];

