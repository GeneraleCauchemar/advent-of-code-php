<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;

/**
 * ❄️ Day 2: Gift Shop ❄️
 *
 * @see https://adventofcode.com/2025/day/2
 */
final class Day02ConundrumSolver extends AbstractConundrumSolver
{
    private array $ids = [];

    public function __construct()
    {
        parent::__construct('2025', '02', ',');
    }

    #[\Override]
    public function warmup(): void
    {
        foreach ($this->getInput() as $part) {
            [$start, $end] = explode('-', $part);
            $range = range((int) $start, (int) $end);

            foreach ($range as $id) {
                $this->ids[] = $id;
            }
        }
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $invalid = 0;

        foreach ($this->ids as $id) {
            $n = \strlen((string) $id);
            if (0 !== $n % 2) {
                continue;
            }

            [$a, $b] = str_split((string) $id, $n / 2);
            if ($a === $b) {
                $invalid += $id;
            }
        }

        return $invalid;
    }

    // //////////////
    // PART 2
    // //////////////

    // KMP algorithm
    public function partTwo(): string|int
    {
        $invalid = 0;

        /**
         * Let:
         * - $n = length of id
         * - $pi = prefix array
         * - $p = candidate period
         */
        foreach ($this->ids as $id) {
            $n = \strlen((string) $id);
            $pi = $this->getPrefix((string) $id, $n);
            $p = $n - $pi[$n - 1];

            /**
             * The string is periodic only if the period
             * is shorter than the full length of the string
             * and the string can be broken down into segments
             * of period length, which determines that the
             * ID is considered invalid
             */
            if (($p < $n) && (0 === $n % $p)) {
                $invalid += $id;
            }
        }

        return $invalid;
    }

    // //////////////
    // METHODS
    // //////////////

    private function getPrefix(string $s, int $n): array
    {
        $pi = array_fill(0, $n, 0);

        // For each position $i, we compute the longest prefix of $s[$i..] that is equal to $s[$j..]
        /**
         * Let:
         * - $i = current position in the string
         * - $j = length of the longest prefix-suffix match ending at $i - 1
         */
        for ($i = 1, $j = 0; $i < $n; $i++) {
            // Shrink prefix until characters are equal or $j = 0
            while (0 < $j && $s[$i] !== $s[$j]) {
                $j = $pi[$j - 1];
            }

            // Extend $j if they match
            if ($s[$i] === $s[$j]) {
                $j++;
            }

            $pi[$i] = $j;
        }

        return $pi;
    }
}
