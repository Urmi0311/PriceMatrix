<?php

namespace Sigma\PriceMatrix\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Sigma\PriceMatrix\Model\PriceMatrix;
use Psr\Log\LoggerInterface;
use Sigma\PriceMatrix\Model\PriceMatrixFactory;
use Sigma\PriceMatrix\Helper\Data as LowestPriceHelper;

/**
 * Class ProductList for view model
 */
class ProductList implements ArgumentInterface
{
    /**
     * @var PriceMatrix
     */
    protected $priceMatrixModel;

    /**
     * @var LowestPriceHelper
     */
    protected $lowestPriceHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PriceMatrixFactory
     */
    protected $priceMatrixFactory;

    /**
     * ProductList constructor.
     * @param PriceMatrixFactory $priceMatrixFactory
     * @param PriceMatrix $priceMatrixModel
     * @param LowestPriceHelper $lowestPriceHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        PriceMatrixFactory $priceMatrixFactory,
        PriceMatrix        $priceMatrixModel,
        LowestPriceHelper  $lowestPriceHelper,
        LoggerInterface    $logger
    ) {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->priceMatrixModel = $priceMatrixModel;
        $this->lowestPriceHelper = $lowestPriceHelper;
        $this->logger = $logger;
    }

    /**
     * Get the lowest price for a product
     *
     * @param int $productId
     */
    public function getLowestPrice($productId)
    {
        $lowestPrice = $this->lowestPriceHelper->getLowPrice($productId);
        return $lowestPrice;
    }

    /**
     * Check if price matrix is available for a product
     *
     * @param int $productId
     * @return mixed
     */
    public function isPriceMatrixAvailable($productId)
    {
        $this->priceMatrixModel->load($productId, 'product_id');
        return $this->priceMatrixModel->getId();
    }

    /**
     * Get the price matrix model
     */
    public function getPriceMatrixModel()
    {
        return $this->priceMatrixModel;
    }
}
