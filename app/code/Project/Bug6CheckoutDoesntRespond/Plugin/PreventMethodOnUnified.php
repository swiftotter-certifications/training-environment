<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug6CheckoutDoesntRespond\Plugin;

use Project\Bug6CheckoutDoesntRespond\Model\EngagedState;
use SwiftOtter\Utils\Model\UnifiedSale;

class PreventMethodOnUnified
{
    private EngagedState $engagedState;

    public function __construct(EngagedState $engagedState)
    {
        $this->engagedState = $engagedState;
    }

    public function afterGet(UnifiedSale $subject, $output)
    {
        if ($this->engagedState->isEngaged()) {
            return null;
        }

        return $output;
    }
}
