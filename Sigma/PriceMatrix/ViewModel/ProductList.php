<?php
namespace Sigma\PriceMatrix\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Sigma\PriceMatrix\Model\PriceMatrix;
use Psr\Log\LoggerInterface;
use Sigma\PriceMatrix\Model\PriceMatrixFactory;


/**
 * Class ProductList for view model
 */
class ProductList implements ArgumentInterface
{
    /**
     * @var PriceMatrix
     */
    protected $priceMatrixModel;



    protected $logger;

    protected $priceMatrixFactory;


    /**
     * ProductList constructor.
     *
     * @param PriceMatrix $priceMatrixModel
     */
    public function __construct(
        PriceMatrixFactory $priceMatrixFactory,
        PriceMatrix $priceMatrixModel,
        LoggerInterface $logger


    )
    {
        $this->priceMatrixFactory = $priceMatrixFactory;
        $this->priceMatrixModel = $priceMatrixModel;
        $this->logger = $logger;

    }

    /**
     * Get the lowest price for a product
     *
     * @param int $productId
     */
    public function getLowestPrice($productId)
    {
        try {
            $this->logger->info("Lowest Price for Product ID: $productId");

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

            $this->logger->info("Lowest Price for Product ID $productId: $lowestPrice");

            return $lowestPrice;
        } catch (\Exception $e) {
            $this->logger->error("Error in getting lowest price for Product ID $productId: " . $e->getMessage());
            return null;
        }
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
