<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;
use App\Entity\Year2025\Day04\DomainLogic;
use App\Entity\Year2025\Day04\Position;

/**
 * ❄️ Day 4: Printing Department ❄️
 *
 * @see https://adventofcode.com/2025/day/4
 */
final class Day04ConundrumSolver extends AbstractConundrumSolver
{
    private array $map;
    private DomainLogic $domainLogic;
    private array $paperRolls;
    private int $toRemoveNextRound;

    public function __construct()
    {
        parent::__construct('2025', '04');
    }

    public function warmup(): void
    {
        $this->map = $this->map();
        $this->domainLogic = new DomainLogic($this->map);
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $this->processTurn();

        return $this->toRemoveNextRound;
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        $removable = $this->toRemoveNextRound;

        while (0 !== $this->toRemoveNextRound) {
            $this->processTurn();
            
            $removable += $this->toRemoveNextRound;
        }

        return $removable;
    }

    // //////////////
    // METHODS
    // //////////////

    private function map(): array
    {
        $map = [];

        foreach ($this->getInput() as $y => $line) {
            $row = [];

            foreach (str_split((string) $line) as $x => $symbol) {
                $position = new Position($y, $x, '@' === $symbol);
                $row[$x] = $position;

                if ($position->isPaperRoll) {
                    $this->paperRolls[] = $position;
                }
            }

            $map[$y] = $row;
        }

        return $map;
    }

    private function processTurn(): void
    {
        $removable = 0;

        foreach ($this->paperRolls as $key => $paperRoll) {
            $adjacentRolls = $this->domainLogic->getAdjacentNodes($paperRoll);
            if (4 <= \count($adjacentRolls)) {
                continue;
            }

            $removable++;
            $this->map[$paperRoll->row][$paperRoll->column] = new Position(
                $paperRoll->row,
                $paperRoll->column,
                false,
            );

            unset($this->paperRolls[$key]);
        }

        // Resetting with rolls removed
        $this->domainLogic = new DomainLogic($this->map);
        $this->toRemoveNextRound = $removable;
    }
}
