<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Common\Action;

use Magento\Integration\Model\Oauth\TokenFactory as OauthTokenFactory;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class CreateApiToken
{
    /** @var OauthTokenFactory */
    private $oauthTokenFactory;

    /** @var UserCollectionFactory */
    private $userCollectionFactory;

    public function __construct(
        OauthTokenFactory $oauthTokenFactory,
        UserCollectionFactory $userCollectionFactory
    ) {
        $this->oauthTokenFactory = $oauthTokenFactory;
        $this->userCollectionFactory = $userCollectionFactory;
    }

    public function execute(): string
    {
        $user = $this->userCollectionFactory->create()
            ->getFirstItem();

        $token = $this->oauthTokenFactory->create()
            ->createAdminToken((int)$user->getId());

        return $token->getToken();
    }
}
