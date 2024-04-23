<?php

namespace Sigma\PriceMatrix\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Sigma\PriceMatrix\Model\PriceMatrix as ProductModel;
use Sigma\PriceMatrix\Model\ResourceModel\PriceMatrix as ProductResource;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class CustomFieldObserver implements ObserverInterface
{
    protected $productResource;
    protected $productModel;
    protected $request;
    protected $logger;

    public function __construct(
        ProductResource $productResource,
        ProductModel $productModel,
        RequestInterface $request,
        LoggerInterface $logger
    )
    {
        $this->productResource = $productResource;
        $this->productModel = $productModel;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $productId = $product->getId();

        $this->logger->info("heyy " . print_r($productId, true));

        $postData = $this->request->getPostValue();

        $this->logger->info("heyy " . print_r($postData, true));

        if ($postData) {
            for ($i = 1; $i <= 5; $i++) {
                if (isset($postData['product']['custom_fieldset']['base_price_container']['display_base_price_' . $i])) {
                    $displayBasePrice = $postData['product']['custom_fieldset']['base_price_container']['display_base_price_' . $i];
                    $displayQty = $postData['product']['custom_fieldset'] ['qty_container']['display_qty_' . $i];

                    $this->logger->info("CustomFieldObserver: Product ID - " . $productId);
                    $this->logger->info("CustomFieldObserver: Display Base Price " . $i . " - " . $displayBasePrice);
                    $this->logger->info("CustomFieldObserver: Display Qty " . $i . " - " . $displayQty);

                    $priceMatrix = $this->productModel->load($productId, 'product_id');
                    if (!$priceMatrix->getId()) {
                        $priceMatrix->setProductId($productId);
                    }
                    $priceMatrix->setData('display_base_price_' . $i, $displayBasePrice);
                    $priceMatrix->setData('display_qty_' . $i, $displayQty);

                    try {
                        $this->productResource->save($priceMatrix);
                        $this->logger->info("CustomFieldObserver: Saved successfully.");
                    } catch (\Exception $e) {
                        $this->logger->error("CustomFieldObserver: Error saving product - " . $e->getMessage());
                    }
                }
            }
        }
    }

}
