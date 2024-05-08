<?php
namespace Sigma\PriceMatrix\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Sigma\PriceMatrix\Helper\Data as LowestPriceHelper;
use Psr\Log\LoggerInterface;

class GetLowestPrice extends Action
{
    /**
     * @var lowestPriceHelper
     */
    protected $lowestPriceHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * GetLowestPrice constructor.
     * @param Context $context
     * @param LowestPriceHelper $lowestPriceHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        LowestPriceHelper $lowestPriceHelper,
        LoggerInterface $logger
    ) {
        $this->lowestPriceHelper = $lowestPriceHelper;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute the controller action to get the lowest price.
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');

        $lowestPrice = $this->lowestPriceHelper->getLowPrice($productId);

        $this->logger->info("Lowest price for Product ID $productId: $lowestPrice");

        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        return $resultJson->setData(['success' => true, 'lowest_price' => $lowestPrice]);
    }
}
