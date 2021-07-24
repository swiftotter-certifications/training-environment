<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Customer\Model\Vat;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CountryResponse
{
    /** @var string */
    private $countryCode;

    /** @var CountryFactory */
    private $countryFactory;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var Vat */
    private $vat;

    public function __construct(string $countryCode, CountryFactory $countryFactory, Vat $vat)
    {
        $this->countryCode = $countryCode;
        $this->countryFactory = $countryFactory;
        $this->vat = $vat;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCountryName(): string
    {
        return $this->countryFactory->create()->loadByCode($this->countryCode)->getName();
    }

    public function getShowCompanySelector(): bool
    {
        return $this->vat->isCountryInEU($this->countryCode);
    }
}
