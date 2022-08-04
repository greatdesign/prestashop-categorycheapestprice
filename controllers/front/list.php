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

        $id_category = (int) Tools::getValue('id_category');

        $category = new Category($id_category);
        if(!$id_category
            or !Validate::isLoadedObject($category)) {
            $category = Category::getRootCategory($id_language);
        }
        
        $cheapestProduct = self::getChepestProductByCategory($category->id, $id_language);
        
        $response['price_start_at_in_this_category'] = $cheapestProduct['price'];
        $response['cheapest_product'] = $cheapestProduct;

        echo json_encode($response);
        die;
    }

    public static function getChepestProductByCategory($id_category = null, $id_language = null)
    {
        $id_language = $id_language ?? Context::getContext()->language->id;
        
        try{
            $category = new Category($id_category);

            $cheapestProduct = $category->getProducts(
                $id_language,
                1,
                1,
                'price',
                'asc'
            );
        }catch(PrestaShopDatabaseException $e){
            $response['error']['message'] = $e->getMessage();
            $response['error']['code'] = $e->getCode();
            echo json_encode($response);
            die;
        }

        $cheapestProduct = array_pop($cheapestProduct);

        return $cheapestProduct;
    }
}
