<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;

/**
 * ❄️ Day 5: Cafeteria ❄️
 *
 * @see https://adventofcode.com/2025/day/5
 */
final class Day05ConundrumSolver extends AbstractConundrumSolver
{
    private array $freshIngredientIdRanges;
    private array $ingredients;

    public function __construct()
    {
        parent::__construct('2025', '05', PHP_EOL . PHP_EOL);
    }

    #[\Override]
    public function warmup(): void
    {
        [$ranges, $ingredients] = $this->getInput();
        $this->freshIngredientIdRanges = array_map(
            static fn($range) => array_map('\intval', explode('-', $range)),
            explode(PHP_EOL, $ranges),
        );
        $this->ingredients = array_map(
            static fn($ingredient) => (int) $ingredient,
            array_filter(explode(PHP_EOL, $ingredients))
        );

        $this->mergeOverlappingRanges();
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $fresh = [];

        foreach ($this->ingredients as $ingredient) {
            if ($this->isWithinFreshIngredientIdRanges($ingredient)) {
                $fresh[] = $ingredient;
            }
        }

        return \count($fresh);
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        $freshIds = 0;

        foreach ($this->freshIngredientIdRanges as $range) {
            $freshIds += ($range[1] - $range[0] + 1);
        }

        return $freshIds;
    }

    // //////////////
    // METHODS
    // //////////////

    private function mergeOverlappingRanges(): void
    {
        usort($this->freshIngredientIdRanges, static fn($a, $b) => $a[0] <=> $b[0]);

        $result = [];
        $current = array_first($this->freshIngredientIdRanges);

        foreach ($this->freshIngredientIdRanges as $range) {
            if ($range[0] <= $current[1]) {
                // Overlaps => merge
                $current[1] = max($current[1], $range[1]);
            } else {
                // No overlap => push and start a new range
                $result[] = $current;
                $current = $range;
            }
        }

        $result[] = $current;
        $this->freshIngredientIdRanges = $result;
    }

    private function isWithinFreshIngredientIdRanges(int $id): bool
    {
        return array_any($this->freshIngredientIdRanges, static fn($range) => $id >= $range[0] && $id <= $range[1]);
    }
}
