<?php

namespace Sigma\PriceMatrix\Model\ResourceModel;

class PriceMatrix extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('sigma_price_matrix', 'entity_id');
    }
}