<?php

namespace Sigma\PriceMatrix\Block;

use Magento\Framework\View\Element\Template;
use Sigma\PriceMatrix\Model\PriceMatrixFactory;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Product;

class CustomPrice extends Template
{
    /**
     * @var PriceMatrixFactory
     */
    protected $priceMatrixFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;


    public $product;

    /**
     * CustomPrice constructor.
     * @param Template\Context $context
     * @param PriceMatrixFactory $priceMatrixFactory
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PriceMatrixFactory $priceMatrixFactory,
        LoggerInterface $logger,
        Product $product,
        array $data = []
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->logger = $logger;
        $this->product = $product;
        parent::__construct($context, $data);
    }

    /**
     * Get the lowest price for the product.
     *
     * @param int $productId
     * @return float|null
     */
    public function getLowestPrice($productId)
    {
        try {
            $this->logger->info("Lowest Price for Product ID: $productId");

            $priceMatrixModel = $this->priceMatrixFactory->create()->load($productId, 'product_id');
//
//            $configurable = $this->product->load($productId);
//            $children = $configurable
//                ->getTypeInstance()
//                ->getUsedProducts($configurable);
//            foreach ($children as $child) {
//                $this->logger->info("Child Product ID: " . $child->getId());
//
//            }

                $lowestPrice = null;

            if ($priceMatrixModel) {
                for ($i = 1; $i <= 10; $i++) {
                    $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);
                    $isChecked = $priceMatrixModel->getData('checkbox_' . $i);

                    if ($basePrice && $isChecked) {
                        if ($lowestPrice === null || $basePrice < $lowestPrice) {
                            $lowestPrice = $basePrice;
                        }
                    }
                }
            }

            $this->logger->info("Lowest Price for Product ID $productId: $lowestPrice");

            return $lowestPrice;
        } catch (\Exception $e) {
            $this->logger->error("Error in getting lowest price for Product ID $productId: " . $e->getMessage());
            return null;
        }
    }
}