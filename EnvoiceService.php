<?php

/**
 * Envoce class to work with Envoice Rest API
 */
class EnvoiceService
{

    private $authKey = '';
    private $authSecret = '';
    private $endpoint = 'https://www.envoice.in/api/';

    /**
     * Authorize
     */
    public function __construct($key, $secret)
    {

        $this->authKey = $key;
        $this->authSecret = $secret;

    }

    /**
     * Get products
     */
    public function getProducts()
    {

        $pageSize = 100;

        // call to envoice server
        $json = $this->get('product/all/?page=1&pageSize=' . $pageSize);

        $products = $json->Result;

        $totalItems = $json->TotalCount;
        $displayed = $json->Count;

        // if we have more products that we recieved
        // make a couple of calls
        if ($totalItems > $displayed) {

            $pages = ceil($totalItems / $pageSize);

            for ($page = 2; $page <= $pages; $j++) {
                $json = $this->get('product/all/?page=' . $page . '&pageSize=' . $pageSize);
                array_merge($products, $json->Result);
            }

        }

        // save to database
        $this->storeProducts($products);

        return $products;

    }

    /**
     * Make a call to API
     *
     * @param String $method Requested method
     *
     * @return array
     */
    public function get($method)
    {

        // attach auth data to request
        $args = array(
            'headers' => array(
                'x-auth-key' => $this->authKey,
                'x-auth-secret' => $this->authSecret,
            ),
        );

        $result = wp_remote_get($this->endpoint . $method, $args);

        $json = json_decode($result['body']);

        return $json;

    }

    /**
     * Save products to database
     */
    public function storeProducts($products)
    {

        // update database with products list
        global $wpdb;
        $table_name = $wpdb->prefix . 'envoice_products';

        foreach ($products as $item) {

            $wpdb->insert(
                $table_name,
                array(
                    'uid' => $item->Id,
                    'name' => $item->Name,
                    'token' => $item->AccessToken,
                )
            );

        }

    }

    /**
     * Return products list in array to use on frontend
     *
     * @return array
     */
    public function getProductsListJson()
    {

        $json = $this->getProducts();
        $products = [];

        foreach ($json as $item) {

            $products[] = [
                'Id' => $item->Id,
                'Name' => $item->Name,
                'Total' => strval($item->TotalAmount),
                'Status' => $this->getHumanStatus($item->Status),
                'Currency' => $item->Currency->Value,
                'StatusCode' => $item->Status,
                'Token' => $item->AccessToken,
            ];

        }



        return $products;

    }

    /**
     * Get human n readable status for product
     *
     * @param int $statusCode Status from API
     *
     * @return String
     */
    public function getHumanStatus($statusCode)
    {

        $statusCode = intval($statusCode);
        $status = '';

        switch ($statusCode) {
            case 0:
                $status = 'Active';
                break;
            case 1:
                $status = 'Not available';
                break;
            case 2:
                $status = 'Inactive';
                break;
            default:
                $status = '';
                break;
        }

        return $status;

    }

}
