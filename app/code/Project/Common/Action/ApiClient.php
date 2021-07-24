<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Common\Action;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ApiClient
{
    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var CreateApiToken */
    private $createApiToken;

    public function __construct(ScopeConfigInterface $scopeConfig, CreateApiToken $createApiToken)
    {
        $this->scopeConfig = $scopeConfig;
        $this->createApiToken = $createApiToken;
    }

    public function execute()
    {
        return new Client([
            'base_uri' => $this->scopeConfig->getValue('web/unsecure/base_url'), // Guzzle doesn't allow multiple paths
            'headers' => [
                'Authorization' => 'Bearer ' . $this->createApiToken->execute()
            ],
            'verify' => false // for development environments ONLY
        ]);
    }
}
