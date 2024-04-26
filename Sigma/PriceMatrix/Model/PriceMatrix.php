<?php

namespace Sigma\PriceMatrix\Model;

class PriceMatrix extends \Magento\Framework\Model\AbstractModel
{
    /**
     * PriceMatrix constructor
     */
    protected function _construct()
    {
        $this->_init(\Sigma\PriceMatrix\Model\ResourceModel\PriceMatrix::class);
    }
}
