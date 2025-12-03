<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;

/**
 * ❄️ Day 3: Lobby ❄️
 *
 * @see https://adventofcode.com/2025/day/3
 */
final class Day03ConundrumSolver extends AbstractConundrumSolver
{
    public function __construct()
    {
        parent::__construct('2025', '03');
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $totalOutputJoltage = 0;

        /**
         * We want the highest digit starting from the left that is
         * not the last digit in the bank, and then the next highest
         * right of it
         */
        foreach ($this->getInput() as $bank) {
            $bank = str_split($bank);
            $chunk = \array_slice($bank, 0, -1);

            $a = max($chunk);
            $b = max(\array_slice($bank, array_search($a, $chunk, true) + 1));

            $totalOutputJoltage += (int) ($a . $b);
        }

        return $totalOutputJoltage;
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        $totalOutputJoltage = 0;

        /**
         * Slicing the line to always manage chunks that
         * stop x steps before the end of the bank but
         * start right before the offset of the previous
         * digit in the joltage sequence, we are still
         * looking for the highest digit in the chunk and
         * updating the offset accordingly
         */
        foreach ($this->getInput() as $bank) {
            $bank = str_split($bank);
            $offset = 0;
            $joltage = '';

            for ($i = -11; 0 >= $i; $i++) {
                $chunk = \array_slice($bank, $offset, 0 === $i ? null : $i, preserve_keys: true);
                $max = max($chunk);
                $joltage .= $max;
                $offset = array_search($max, $chunk, true) + 1;
            }

            $totalOutputJoltage += (int) ($joltage);
        }

        return $totalOutputJoltage;
    }

    // //////////////
    // METHODS
    // //////////////
}
