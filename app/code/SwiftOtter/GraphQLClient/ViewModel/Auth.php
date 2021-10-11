<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GraphQLClient\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Backend\Model\Auth\Session as AdminSession;

class Auth implements ArgumentInterface
{
    private UrlInterface $urlBuilder;
    private TokenFactory $tokenFactory;
    private AdminSession $adminSession;

    public function __construct(
        TokenFactory $tokenFactory,
        UrlInterface $urlBuilder,
        AdminSession $adminSession
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->tokenFactory = $tokenFactory;
        $this->adminSession = $adminSession;
    }

    public function getToken()
    {
        return $this->tokenFactory->create()
            ->createAdminToken($this->adminSession->getUser()->getId())
            ->getToken();
    }

    public function getUrl()
    {
        return $this->urlBuilder->getBaseUrl() . 'graphql';
    }
}
