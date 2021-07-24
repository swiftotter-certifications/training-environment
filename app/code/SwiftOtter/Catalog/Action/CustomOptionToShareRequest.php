<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Action;

use Magento\Framework\Api\AttributeInterface as Attribute;
use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterfaceFactory as ShareRequestFactory;
use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface as ShareRequest;

class CustomOptionToShareRequest
{
    /** @var ShareRequestFactory */
    private $shareRequestFactory;

    public function __construct(ShareRequestFactory $shareRequestFactory)
    {
        $this->shareRequestFactory = $shareRequestFactory;
    }

    public function execute(?array $values): ShareRequest
    {
        /** @var ShareRequest $request */
        $request = $this->shareRequestFactory->create();
        if (!$values || !count($values)) {
            return $request;
        }

        if (!empty($values['email'])) {
            $request->setEmail($values['email']);
        }

        if (!empty($values['enabled'])) {
            $request->setEnabled($values['enabled']);
        }

        if (!empty($values['send'])) {
            $request->setSend($values['send']);
        }

        return $request;
    }
}
