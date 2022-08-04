<?php

use FacebookAds\Object\BusinessDataAPI\Content;

class DummymodulenameListModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        header('Content-Type: application/json');
        $response = [];

        $id_language = (int) Tools::getValue('id_language', $this->context->language->id);
        $id_shop = (int) Tools::getValue('id_shop', $this->context->shop->id);

        $id_category = (int) Tools::getValue('id_category');

        $category = new Category($id_category);
        if(!$id_category
            or !Validate::isLoadedObject($category)) {
            $category = Category::getRootCategory($id_language);
        }
        
        $cheapestProduct = $category->getProducts(
            $this->context->language->id,
            1,
            1,
            'price',
            'asc'
        );

        $cheapestProduct = array_pop($cheapestProduct);
        
        $response['price_start_at_in_this_category'] = $cheapestProduct['price'];
        $response['cheapest_product'] = $cheapestProduct;
        $response['category'] = $category;

        echo json_encode($response);
        die;
    }
}
