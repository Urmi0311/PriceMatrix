<?php

namespace Sigma\PriceMatrix\Block\Product;

use Magento\Framework\View\Element\Template\Context;
use Sigma\PriceMatrix\Model\PriceMatrix;
use Magento\Catalog\Model\ProductRepository;

class PriceTiers extends \Magento\Framework\View\Element\Template
{
    /**
     * @var PriceMatrix
     */
    protected $priceMatrix;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * PriceTiers constructor.
     * @param Context $context
     * @param PriceMatrix $priceMatrix
     * @param ProductRepository $productRepository
     * @param array $data
     */
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

    /**
     * Retrieve the PriceMatrix model for the current product.
     *
     * @return PriceMatrix
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPriceMatrixModel()
    {
        $productId = $this->getRequest()->getParam('id');
        $product = $this->productRepository->getById($productId);

        return $this->priceMatrix->load($product->getId(), 'product_id');
    }
}
