<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Action;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class PushDetailsToWebservice
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(int $orderId, array $orderDetails): bool
    {
        try {
            return true;
            // Use GuzzleHttp (http://docs.guzzlephp.org/en/stable/) to send the data to our webservice.

            $client = new Client();
            $response = $client->post('https://swiftotter.com', [
                'json' => $orderDetails
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \InvalidArgumentException('There was a problem: ' . $response->getBody());
            }

            $body = (string)$response->getBody();

        } catch (\Exception $ex) {
            $this->logger->critical($ex->getMessage(), [
                'order_id' => $orderId,
                'details' => $orderDetails
            ]);

            throw $ex;
        }
    }
}