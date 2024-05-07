<?php
namespace Sigma\PriceMatrix\Helper;

use Sigma\PriceMatrix\Model\PriceMatrixFactory;
use Psr\Log\LoggerInterface;

class Data
{
    /**
     * @var PriceMatrixFactory
     */
    protected $priceMatrixFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param PriceMatrixFactory $priceMatrixFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        PriceMatrixFactory $priceMatrixFactory,
        LoggerInterface $logger
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->logger = $logger;
    }

    /**
     * Get the lowest price for the product.
     *
     * @param int $productId
     * @return float|null
     */
    public function getLowPrice($productId)
    {
        try {
            $priceMatrixModel = $this->priceMatrixFactory->create()->load($productId, 'product_id');

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

            return $lowestPrice;
        } catch (\Exception $e) {
            $this->logger->error("Error in getting lowest price for Product ID $productId: " . $e->getMessage());
            return null;
        }
    }
}
