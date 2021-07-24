<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2018/03/26
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Model;

class IpDetails extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\SwiftOtter\Customer\Model\ResourceModel\IpDetails::class);
    }

    public function getId()
    {
        return (int)$this->getData('id');
    }

    public function getIp(): string
    {
        return (string)$this->getData('name');
    }

    public function setIp(string $ip)
    {
        $this->setData('ip', $ip);
    }

    public function getCountryCode(): string
    {
        return (string)$this->getData('country_code');
    }

    public function setCountryCode(string $countryCode)
    {
        $this->setData('country_code', $countryCode);
    }

    public function getCurrency(): string
    {
        return (string)$this->getData('currency');
    }

    public function setCurrency(string $currency)
    {
        return $this->setData('currency', $currency);
    }

    public function getCountry()
    {
        return $this->getResponse()['country'] ?? 'United States';
    }

    public function getResponse(): array
    {
        try {
            return json_decode($this->getData('response'), true);
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function setResponse(array $response)
    {
        return $this->setData('response', json_encode($response));
    }
}
