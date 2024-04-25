<?php
namespace Sigma\PriceMatrix\Observer;

use Magento\Framework\Event\ObserverInterface;

class ReCalculate implements ObserverInterface
{
    protected $priceMatrixFactory;

    public function __construct(
        \Sigma\PriceMatrix\Model\PriceMatrixFactory $priceMatrixFactory
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        foreach ($quote->getAllItems() as $item) {
            $product = $item->getProduct();
            $qty = $item->getQty();

            // Load price matrix data based on product
            $priceMatrixModel = $this->priceMatrixFactory->create()->load($product->getId(), 'product_id');

            if ($priceMatrixModel->getId()) {
                $customPrice = null;
                for ($i = 1; $i <= 5; $i++) {
                    $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);
                    $tierQty = $priceMatrixModel->getData('display_qty_' . $i);

                    if ($basePrice && $tierQty && $qty >= $tierQty) {
                        $customPrice = $basePrice;
                    }
                }

                if ($customPrice !== null) {
                    $item->setCustomPrice($customPrice);
                    $item->setOriginalCustomPrice($customPrice);
                    $item->getProduct()->setIsSuperMode(true);
                }
            }
        }

        return $this;
    }
}
