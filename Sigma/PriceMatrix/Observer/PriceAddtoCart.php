<?php

namespace Sigma\PriceMatrix\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class PriceAddtoCart implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AdjustPrice constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $this->logger->info("AdjustPrice observer called.");

        try {
            $item = $observer->getEvent()->getData('quote_item');
            $childProduct = $item->getOptionByCode('simple_product');
            if ($childProduct) {
                $productId = $childProduct->getProduct()->getId();
                $this->logger->info("Product ID: " . $productId);

                $qty = $item->getQty();

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $priceMatrixModel = $objectManager->create(\Sigma\PriceMatrix\Model\PriceMatrix::class)
                    ->load($productId, 'product_id');
                if ($priceMatrixModel) {
                    $this->logger->info("PriceMatrixModel loaded successfully.");

                    $customPrice = null;

                    for ($i = 1; $i <= 10; $i++) {
                        $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);
                        $tierQty = $priceMatrixModel->getData('display_qty_' . $i);
                        $isChecked = $priceMatrixModel->getData('checkbox_' . $i);

                        $this->logger->info("Tier $i - Qty: $tierQty, Base Price: $basePrice, IsChecked: $isChecked");

                        if ($isChecked && $basePrice && $tierQty && $qty >= $tierQty) {
                            $customPrice = $basePrice;
                        }
                    }

                    if ($customPrice !== null) {
                        $this->logger->info("Custom price set: $customPrice");
                        $item->setCustomPrice($customPrice);
                        $item->setOriginalCustomPrice($customPrice);
                        $item->getProduct()->setIsSuperMode(true);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("Error in AdjustPrice observer: " . $e->getMessage());
        }
    }
}
