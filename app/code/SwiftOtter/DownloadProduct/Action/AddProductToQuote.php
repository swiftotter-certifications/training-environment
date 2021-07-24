<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/26/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Action;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote as QuoteModel;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface as OrderDetails;

class AddProductToQuote
{
    public function execute(QuoteModel $quote, OrderDetails $orderDetails, Product $product)
    {
        $share = [];
        if ($orderDetails->getShare()
            && $orderDetails->getShare()->getEnabled()) {
            $share = [
                'enabled' => true,
                'send' => $orderDetails->getShare()->getSend(),
                'email' => $orderDetails->getShare()->getEmail()
            ];
        }

        $product->addCustomOption('share', json_encode($share));

        return $quote->addProduct($product, new DataObject(['qty' => 1, 'order_details' => $orderDetails]));
    }
}
