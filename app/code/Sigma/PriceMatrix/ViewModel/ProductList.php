<?php
namespace Sigma\PriceMatrix\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Sigma\PriceMatrix\Model\PriceMatrix;

class ProductList implements ArgumentInterface
{
    protected $customPriceBlock;
    protected $priceMatrixModel;

    public function __construct(
        \Sigma\PriceMatrix\Block\CustomPrice $customPriceBlock,
        PriceMatrix $priceMatrixModel
    ) {
        $this->customPriceBlock = $customPriceBlock;
        $this->priceMatrixModel = $priceMatrixModel;
    }

    public function getLowestPrice($productId)
    {
        return $this->customPriceBlock->getLowestPrice($productId);
    }

    public function isPriceMatrixAvailable($productId)
    {
        $this->priceMatrixModel->load($productId, 'product_id');
        return $this->priceMatrixModel->getId();
    }
}
