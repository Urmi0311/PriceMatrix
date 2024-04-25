<?php

namespace Sigma\PriceMatrix\Model;

class PriceMatrix extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Sigma\PriceMatrix\Model\ResourceModel\PriceMatrix::class);
    }
}