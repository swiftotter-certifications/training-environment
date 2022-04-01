<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 4/1/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Controller\Environment;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;

class Index implements HttpGetActionInterface
{
    const PARAM_NAME = 'INIT_PARAMETER';

    private RawFactory $rawFactory;
    private ScopeConfigInterface $scopeConfig;
    private ?string $injectedEnvironmentVariable;

    public function __construct(
        RawFactory $rawFactory,
        ScopeConfigInterface $scopeConfig,
        ?string $injectedEnvironmentVariable = null
    ) {
        $this->rawFactory = $rawFactory;
        $this->scopeConfig = $scopeConfig;
        $this->injectedEnvironmentVariable = $injectedEnvironmentVariable;
    }

    /**
     * \Magento\Config\Model\Placeholder\Environment::generate
     * \Magento\Framework\App\Config\PostProcessorComposite::process
     *
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $raw = $this->rawFactory->create();

        $contents = [
            "<b>Via scope configuration:</b>",
            $this->scopeConfig->getValue('sales/remote/username'),
            "",
            "<b>Via <code>init_parameter</code>:</b>",
            $this->injectedEnvironmentVariable
        ];

        $raw->setContents(implode('<br/>', $contents));
        return $raw;
    }
}
