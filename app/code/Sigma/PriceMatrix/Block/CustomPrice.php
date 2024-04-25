<?php

namespace Sigma\PriceMatrix\Block;

use Magento\Catalog\Model\Product;

class CustomPrice extends \Magento\Framework\View\Element\Template
{
    protected $productRepository;
    protected $priceMatrixModel;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Sigma\PriceMatrix\Model\PriceMatrix $priceMatrixModel,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->priceMatrixModel = $priceMatrixModel;
        parent::__construct($context, $data);
    }

    public function getCustomPriceForProduct(Product $product)
    {
        $productId = $product->getId();
        $priceMatrix = $this->priceMatrixModel->load($productId, 'product_id');

        if ($priceMatrix) {
            $qty = 1; // You can adjust this value if needed
            for ($i = 1; $i <= 5; $i++) {
                $basePrice = $priceMatrix->getData('display_base_price_' . $i);
                $tierQty = $priceMatrix->getData('display_qty_' . $i);

                if ($basePrice && $tierQty) {
                    if ($qty >= $tierQty) {
                        return $basePrice;
                    }
                }
            }
        }

        return null;
    }
}
