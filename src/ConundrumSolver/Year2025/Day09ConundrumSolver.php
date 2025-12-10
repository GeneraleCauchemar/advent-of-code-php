<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;

/**
 * ❄️ Day 9: Movie Theater ❄️
 *
 * @see https://adventofcode.com/2025/day/9
 */
final class Day09ConundrumSolver extends AbstractConundrumSolver
{
    public function __construct()
    {
        parent::__construct('2025', '09');
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $surfaces = [];

        foreach ($this->getPairs() as [$a, $b]) {
            $surfaces[] = (abs($a[0] - $b[0]) + 1) * (abs($a[1] - $b[1]) + 1);
        }

        return max($surfaces);
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        return parent::partTwo();
    }

    // //////////////
    // METHODS
    // //////////////

    private function getTiles(): array
    {
        $tiles = [];
        foreach ($this->getInput() as $line) {
            $tiles[] = array_map('\intval', explode(',', $line));
        }

        return $tiles;
    }

    private function getPairs(): array
    {
        $tiles = $this->getTiles();

        return array_reduce(
            $tiles,
            static function ($result, $item1) use ($tiles) {
                $pairs = array_map(
                    static function ($item2) use ($item1) {
                        return [$item1, $item2];
                    },
                    \array_slice(
                        $tiles,
                        array_search($item1, $tiles, true) + 1
                    )
                );

                return array_merge($result, $pairs);
            },
            []
        );
    }
}
