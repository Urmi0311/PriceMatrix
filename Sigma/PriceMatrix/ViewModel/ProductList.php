<?php
namespace Sigma\PriceMatrix\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Sigma\PriceMatrix\Model\PriceMatrix;

/**
 * Class ProductList for view model
 */
class ProductList implements ArgumentInterface
{
    /**
     * @var \Sigma\PriceMatrix\Block\CustomPrice
     */
    protected $customPriceBlock;

    /**
     * @var PriceMatrix
     */
    protected $priceMatrixModel;

    /**
     * ProductList constructor.
     *
     * @param \Sigma\PriceMatrix\Block\CustomPrice $customPriceBlock
     * @param PriceMatrix $priceMatrixModel
     */
    public function __construct(
        \Sigma\PriceMatrix\Block\CustomPrice $customPriceBlock,
        PriceMatrix $priceMatrixModel
    ) {
        $this->customPriceBlock = $customPriceBlock;
        $this->priceMatrixModel = $priceMatrixModel;
    }

    /**
     * Get the lowest price for a product
     *
     * @param int $productId
     */
    public function getLowestPrice($productId)
    {
        return $this->customPriceBlock->getLowestPrice($productId);
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
