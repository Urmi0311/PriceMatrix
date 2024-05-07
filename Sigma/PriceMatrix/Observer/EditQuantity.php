<?php
namespace Sigma\PriceMatrix\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Recalculate for the mini cart
 */

class EditQuantity implements ObserverInterface
{
    /**
     * @var \Sigma\PriceMatrix\Model\PriceMatrixFactory
     */
    protected $priceMatrixFactory;

    /**
     * ReCalculate constructor.
     *
     * @param \Sigma\PriceMatrix\Model\PriceMatrixFactory $priceMatrixFactory
     */
    public function __construct(
        \Sigma\PriceMatrix\Model\PriceMatrixFactory $priceMatrixFactory
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        foreach ($quote->getAllItems() as $item) {
            $product = $item->getProduct();
            $qty = $item->getQty();

            $childProduct = $item->getOptionByCode('simple_product');
            if ($childProduct) {
                $product = $childProduct->getProduct();
            }

            $priceMatrixModel = $this->priceMatrixFactory->create()->load($product->getId(), 'product_id');

            if ($priceMatrixModel->getId()) {
                $customPrice = null;
                for ($i = 1; $i <= 10; $i++) {
                    $basePrice = $priceMatrixModel->getData('display_base_price_' . $i);
                    $tierQty = $priceMatrixModel->getData('display_qty_' . $i);
                    $isChecked = $priceMatrixModel->getData('checkbox_' . $i);

                    if ($isChecked && $basePrice && $tierQty && $qty >= $tierQty) {
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
