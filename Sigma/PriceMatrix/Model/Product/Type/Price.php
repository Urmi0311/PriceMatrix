<?php
namespace Sigma\PriceMatrix\Model\Product\Type;

use Psr\Log\LoggerInterface;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get final price of product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param float $qty
     * @return float
     */
    public function getFinalPrice($product, $qty = null)
    {
        $price = $product->getPrice();

        $this->logger->info("ewe" . $price);


        if ($qty) {
            $priceMatrixModel = $product->getPriceMatrixModel();

            for ($i = 1; $i <= 5; $i++) {
                $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);
                $tierQty = $priceMatrixModel->getData('display_qty_' . $i);

                $this->logger->info("Tier $i - Qty: $tierQty, Base Price: $basePrice");

                if ($basePrice && $tierQty) {
                    if ($qty <= $tierQty) {
                        $price = $basePrice;
                        break;
                    }
                }
            }
        }

        return $price;
    }
}
