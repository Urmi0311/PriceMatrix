<?php
namespace Sigma\PriceMatrix\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Sigma\PriceMatrix\Model\PriceMatrixFactory;
use Psr\Log\LoggerInterface;

class GetLowestPrice extends Action
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
     * GetLowestPrice constructor.
     * @param Context $context
     * @param PriceMatrixFactory $priceMatrixFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PriceMatrixFactory $priceMatrixFactory,
        LoggerInterface $logger
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute the controller action to get the lowest price.
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $this->logger->info("Request received to get lowest price for Product ID: $productId");

        $lowestPrice = $this->getLowestPrice($productId);

        $this->logger->info("Lowest price for Product ID $productId: $lowestPrice");

        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        return $resultJson->setData(['success' => true, 'lowest_price' => $lowestPrice]);
    }

    /**
     * Get the lowest price for the product.
     *
     * @param int $productId
     * @return float|null
     */
    protected function getLowestPrice($productId)
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
