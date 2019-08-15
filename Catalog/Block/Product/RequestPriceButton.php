<?php
/**
 * Catalog request price button
 */
namespace Smile\Catalog\Block\Product;

use Magento\Framework\View\Element\Template;

/**
 * Class RequestPriceButton
 *
 * @package Smile\Catalog\Block\Product
 */
class RequestPriceButton extends Template
{
    /**
     * Get button label
     *
     * @return string
     */
    public function getButtonLabel()
    {
        return __('Request Price');
    }
}
