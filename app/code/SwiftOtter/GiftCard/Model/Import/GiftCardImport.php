<?php

declare(strict_types=1);

namespace SwiftOtter\GiftCard\Model\Import;

class GiftCardImport
{
    /**
     * Creates a data object to import into Gift Card model
     *
     * @param array $csvData
     * @return array
     */
    public function import(array $csvData): array
    {
        $headers = array_shift($csvData);
        $rows= [];

        foreach ($csvData as $data) {
            $rows[] = array_combine($headers, $data);
        }

        return $rows;
    }
}
