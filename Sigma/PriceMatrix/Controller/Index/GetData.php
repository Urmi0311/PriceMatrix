<?php

namespace Sigma\PriceMatrix\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Sigma\PriceMatrix\Model\PriceMatrix;

class GetData extends Action
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var PriceMatrix
     */
    protected $priceMatrix;

    /**
     * GetData constructor.
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param PriceMatrix $priceMatrix
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        PriceMatrix $priceMatrix
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->priceMatrix = $priceMatrix;
    }

    /**
     *  Execute method for configurable
     */
    public function execute()
    {
        try {
            $productId = $this->getRequest()->getParam('product_id');
            $this->logger->info('Received product ID: ' . $productId);

            $product = $this->productRepository->getById($productId);

            $priceMatrix = $this->priceMatrix->load($product->getId(), 'product_id');

            if ($priceMatrix && $priceMatrix->getId()) {
                $this->logger->info('Loaded price matrix model: ' . json_encode($priceMatrix->getData()));
            } else {
                $this->logger->info('Price matrix model not available.');
            }

            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setContents(json_encode(['priceMatrix' => $priceMatrix->getData()]));
            return $resultRaw;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->resultFactory->create(ResultFactory::TYPE_RAW)
                ->setHttpResponseCode(500)->setContents($e->getMessage());
        }
    }
}
