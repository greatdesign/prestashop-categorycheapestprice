<?php

class ExtendedCategory extends Category
{
    /**
     * Returns cheapest product of this category
     *
     * @param int $id_language Language ID
     *
     * @return Product
     *
     * @throws PrestaShopDatabaseException
     */
    public function getCheapestProduct($id_language = null) : Product
    {
        $id_language = $id_language ?? Context::getContext()->language->id;
        
        $cheapestProduct = $this->getProducts(
            $id_language,
            1,
            1,
            'price',
            'asc'
        );

        // validate results
        if(empty($cheapestProduct)) {
            throw new PrestaShopException($this->trans('No products found in category of id: %d', [$this->id], 'Modules.Dummymodulename.Exceptions'));
        }

        // pop the item from array
        $cheapestProduct = array_pop($cheapestProduct);

        // create new product
        $product = new Product($cheapestProduct['id_product']);

        // validate loaded
        if(!Validate::isLoadedObject($product)) {
            throw new PrestaShopException($this->trans('Product failed to load', [], 'Modules.Dummymodulename.Exceptions'));
        }

        return $product;
    }
}