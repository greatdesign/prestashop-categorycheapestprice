<?php

class DummymodulenameListallModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        
        $id_language = (int) Tools::getValue('id_language', $this->context->language->id);

        $categories = Category::getSimpleCategories($id_language);

        $cheapestProductByCategory = [];
        $cheapestProductPriceByCategory = [];
        foreach($categories as $category) {
            $cheapestProduct = self::getChepestProductByCategory($category['id_category'], $id_language);
            $cheapestProductByCategory[$category['id_category']] = $cheapestProduct;
            $cheapestProductPriceByCategory[$category['id_category']] = $cheapestProduct['price'];
        }
        
        header('Content-Type: application/json');
        $response['cheapest_product_price_by_category'] = $cheapestProductPriceByCategory;
        $response['cheapest_product_by_category'] = $cheapestProductByCategory;
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
