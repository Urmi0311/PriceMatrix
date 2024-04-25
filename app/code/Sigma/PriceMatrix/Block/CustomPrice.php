<?php

namespace Sigma\PriceMatrix\Block;

use Magento\Framework\View\Element\Template;
use Sigma\PriceMatrix\Model\PriceMatrixFactory;
use Psr\Log\LoggerInterface;

class CustomPrice extends Template
{
    protected $priceMatrixFactory;
    protected $logger;

    public function __construct(
        Template\Context $context,
        PriceMatrixFactory $priceMatrixFactory,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    public function getLowestPrice($productId)
    {
        try {
            $this->logger->info("Lowest Price for Product rrrID");

            $priceMatrixModel = $this->priceMatrixFactory->create()->load($productId, 'product_id');

            $lowestPrice = null;

            if ($priceMatrixModel) {
                for ($i = 1; $i <= 5; $i++) {
                    $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);

                    if ($basePrice) {
                        if ($lowestPrice === null || $basePrice < $lowestPrice) {
                            $lowestPrice = $basePrice;
                        }
                    }
                }
            }

            $this->logger->info("Lowest Price for Product ID $productId: $lowestPrice");

            return $lowestPrice;
        } catch (\Exception $e) {
            // Log any exceptions
            $this->logger->error("Error in getting lowest price for Product ID $productId: " . $e->getMessage());
            return null;
        }
    }

}
