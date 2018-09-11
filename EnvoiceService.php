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

        // call to envoice server
        $data = $this->get('product/all');

        // save to database
        $this->storeProducts($data);

        return $data;

    }

    /**
     * Make a call to API
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

        return $result;

    }

    /**
     * Save products to database
     */
    public function storeProducts($data)
    {

        // update database with products list
        global $wpdb;
        $table_name = $wpdb->prefix . 'envoice_products';

        $json = json_decode($data['body']);

        foreach ($json->Result as $item) {

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

        $data = $this->getProducts();
        $data = json_decode($data['body']);

        foreach ($data->Result as $item) {
            $products[] = [
                'Id' => $item->Id,
                'Name' => $item->Name,
                'Total' => strval($item->TotalAmount),
                'Status' => $this->getHumanStatus($item->Status),
                'Currency' => $item->Currency->Value,
                'StatusCode' => $item->Status,
                'Token'=>$item->AccessToken
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
