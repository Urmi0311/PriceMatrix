<?php
namespace Sigma\PriceMatrix\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Sigma\PriceMatrix\Model\PriceMatrix;

/**
 * Price Matrix field modifier for product form
 */

class NewField extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var PriceMatrix
     */
    private $priceMatrixModel;

    /**
     * NewField constructor.
     * @param LocatorInterface $locator
     * @param PriceMatrix $priceMatrixModel
     */
    public function __construct(
        LocatorInterface $locator,
        PriceMatrix $priceMatrixModel
    ) {
        $this->locator = $locator;
        $this->priceMatrixModel = $priceMatrixModel;
    }

    /**
     * Modify data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/urmi.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $product = $this->locator->getProduct();
        $productId = $product->getId();

        $logger->info($productId);

        if ($productId) {
            $priceMatrix = $this->priceMatrixModel->load($productId, 'product_id');
            if ($priceMatrix->getId()) {
                for ($i = 1; $i <= 10; $i++) {
                    $data[$productId]['product']['custom_fieldset']['base_price_container']
                    ['display_base_price_' . $i] = $priceMatrix->getData('display_base_price_' . $i);

                    $data[$productId]['product']['custom_fieldset']['qty_container']
                    ['display_qty_' . $i] = $priceMatrix->getData('display_qty_' . $i);

                    $data[$productId]['product']['custom_fieldset']['checkbox_container']
                    ['checkbox_' . $i] = $priceMatrix->getData('checkbox_' . $i);
                }
            }

        }

        return $data;
    }

    /**
     * Modify meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'custom_fieldset' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Price Matrix'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.product.custom_fieldset',
                                'collapsible' => true,
                                'sortOrder' => 5,
                            ],
                        ],
                    ],
                    'children' => [
                        'qty_container' => $this->getQtyContainer(),
                        'base_price_container' => $this->getBasePriceContainer(),
                        'checkbox_container' => $this->getCheckboxContainer(),

                    ],
                ]
            ]
        );
        return $meta;
    }

    /**
     * Get base price container
     *
     * @return array
     */
    public function getBasePriceContainer()
    {
        $container = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Base Price'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => 'base_price_container',
                        'formElement' => 'checkbox',
                        'sortOrder' => 30,
                        'additionalClasses' => 'admin__field-wide',
                    ],
                ],
            ],
            'children' => []
        ];

        for ($i = 1; $i <= 10; $i++) {
            $container['children']['display_base_price_' . $i] = $this->getBasePriceField($i);
        }

        return $container;
    }

    /**
     * Get base price field
     *
     * @param int $index
     * @return array
     */
    public function getBasePriceField($index)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __(''),
                        'componentType' => Field::NAME,
                        'formElement' => 'input',
                        'dataScope' => 'display_base_price_' . $index,
                        'sortOrder' => $index,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get quantity container
     *
     * @return array
     */
    public function getQtyContainer()
    {
        $container = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Quantity'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => 'qty_container',
                        'sortOrder' => 20,
                        'additionalClasses' => 'admin__field-wide',
                    ],
                ],
            ],
            'children' => []
        ];

        for ($i = 1; $i <= 10; $i++) {
            $container['children']['display_qty_' . $i] = $this->getQtyField($i);
        }

        return $container;
    }

    /**
     * Get quantity field
     *
     * @param int $index
     * @return array
     */
    public function getQtyField($index)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __(''),
                        'componentType' => Field::NAME,
                        'formElement' => 'input',
                        'dataScope' => 'display_qty_' . $index,
                        'sortOrder' => $index,
                    ],
                ],
            ],
        ];
    }
    public function getCheckboxContainer()
    {
        $container = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Checkbox'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => 'checkbox_container',
                        'sortOrder' => 40,
                        'additionalClasses' => 'admin__field-wide',
                    ],
                ],
            ],
            'children' => []
        ];

        for ($i = 1; $i <= 10; $i++) {
            $container['children']['checkbox_' . $i] = $this->getCheckboxField($i);
        }

        return $container;
    }

    /**
     * Get quantity field
     *
     * @param int $index
     * @return array
     */
    public function getCheckboxField($index)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __(''),
                        'componentType' => Field::NAME,
                        'formElement' => 'checkbox',
                        'dataScope' => 'checkbox_' . $index,
                        'sortOrder' => $index,
                        'checked' => '1',
                        'value' => '1',

                    ],
                ],
            ],
        ];
    }
}
