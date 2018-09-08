<?php

/**
 * Products list
 *
 * Generate page with products and jsgrid
 * jsgrid initialized with php variable "products"
 * and then json_encode to use in jsgrid
 *
 * At first we request to Envoice API and update products list
 *
 */
function envoice_products_list_html()
{

    // call to envoice api and check result
    $api = new EnvoiceService(get_option('auth_key'), get_option('auth_secret'));
    $data = $api->getProducts();

    $data = json_decode($data['body']);

    // prepare the array for table
    $products = [];
    foreach ($data->Result as $item) {
        $products[] = [
            'Id' => $item->Id,
            'Name' => $item->Name,
            'Total' => strval($item->TotalAmount),
            'Status' => $item->Status,
            'Currency' => $item->Currency->Value,
        ];
    }

    ?>

    <div class="wrap">

    <h1>Products</h1>

    <div id="envoice-container">

        <div class="envoice-content">

            <div class="products-table">
                <div id="jsGrid"></div>
            </div>

        </div>


    </div>

    <script>
        var products = <?php echo json_encode($products) ?>;
    </script>


    <?php
}