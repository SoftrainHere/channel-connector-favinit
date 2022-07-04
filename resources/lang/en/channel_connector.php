<?php declare(strict_types = 1);

return [
    'title'          => 'Favinit',
    'title_singular' => 'Favinit',
    'fields'         => [
        'id'                => 'ID',
        'created_at'        => 'Created at',
        'updated_at'        => 'Updated at',
    ],
    'errors'        => [
        'no_api_endpoint' => 'Api-endpoint was not specified',
        'price_set_update' => 'Price-set update error for Vendor(:vendor_id) / Product(:product_id)',
        'inventory_set_update' => 'Inventory-set update error for Vendor(:vendor_id) / Product(:product_id)',
        'no_category_id_mapped_exist' => 'No category-id mapped exists for category(:category_id) of product(:product_id)',
    ],
];
