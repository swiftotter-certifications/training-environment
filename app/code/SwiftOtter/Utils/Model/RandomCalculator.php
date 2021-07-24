<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model;

class RandomCalculator
{
    public function asNormalString(int $length)
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->string($input, $length);
    }

    public function asLowerString(int $length)
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyz';
        return $this->string($input, $length);
    }

    public function getRandomValue(array $input)
    {
        $selection = array_rand($input, 1);
        return $input[$selection];
    }

    private function string(string $input, int $length): string
    {
        $totalLength = strlen($input);
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $selectedCharacter = $input[mt_rand(0, $totalLength - 1)];
            $output .= $selectedCharacter;
        }

        return $output;
    }

    public function getRandomDate(?\DateTime $start = null, ?\DateTime $end = null): \DateTime
    {
        if (!$start) {
            $start = new \DateTime();
        }

        if (!$end) {
            $end = (new \DateTime())->modify('+4 year');
        }

        $days = (int)$start->diff($end)->format('%a');

        $start->add(new \DateInterval(sprintf('P%sD', rand(1, $days))));
        $start->setDate($start->format('Y'), $start->format('m'), $start->format('t'));

        return $start;
    }
}
