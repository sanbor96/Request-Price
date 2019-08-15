<?php
/**
 * Catalog request price form
 */
namespace Smile\Catalog\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;

/**
 * Class RequestPriceForm
 *
 * @package Smile\Catalog\Block\Product
 */
class RequestPriceForm extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * RequestPriceForm constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get form action
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('smile_catalog/requestprice/save');
    }

    /**
     * Get current product
     *
     * @return object
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }
}
