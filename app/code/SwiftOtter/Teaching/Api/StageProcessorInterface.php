<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/1/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Api;

interface StageProcessorInterface
{
    /**
     * @return \SwiftOtter\Teaching\Api\ProcessorResponseInterface
     */
    public function execute(): ProcessorResponseInterface;
}
