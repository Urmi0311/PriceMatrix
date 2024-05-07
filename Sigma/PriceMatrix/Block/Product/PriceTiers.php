<?php

namespace Sigma\PriceMatrix\Block\Product;

use Magento\Framework\View\Element\Template\Context;
use Sigma\PriceMatrix\Model\PriceMatrix;
use Magento\Catalog\Model\ProductRepository;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PriceTiers constructor.
     * @param Context $context
     * @param PriceMatrix $priceMatrix
     * @param ProductRepository $productRepository
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        PriceMatrix $priceMatrix,
        ProductRepository $productRepository,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->priceMatrix = $priceMatrix;
        $this->productRepository = $productRepository;
        $this->logger = $logger;

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
        $this->logger->info('Received product ID: ' . $productId);

        $product = $this->productRepository->getById($productId);

        return $this->priceMatrix->load($product->getId(), 'product_id');
    }
}
