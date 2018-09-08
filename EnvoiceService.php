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
                'x-auth-secret' => $this->authSecret
            )
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

        foreach ($json->Result as $item){

            $wpdb->insert(
                $table_name,
                array(
                    'uid' => $item->Id,
                    'name' => $item->Name,
                    'token' => $item->AccessToken
                )
            );

        }

    }

}