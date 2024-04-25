<?php

namespace Sigma\PriceMatrix\Block\Product;

use Magento\Framework\View\Element\Template\Context;
use Sigma\PriceMatrix\Model\PriceMatrix;
use Magento\Catalog\Model\ProductRepository;

class PriceTiers extends \Magento\Framework\View\Element\Template
{
    protected $priceMatrix;
    protected $productRepository;

    public function __construct(
        Context $context,
        PriceMatrix $priceMatrix,
        ProductRepository $productRepository,
        array $data = []
    ) {
        $this->priceMatrix = $priceMatrix;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function getPriceMatrixModel()
    {
        $productId = $this->getRequest()->getParam('id');
        $product = $this->productRepository->getById($productId);

        return $this->priceMatrix->load($product->getId(), 'product_id');
    }
}
