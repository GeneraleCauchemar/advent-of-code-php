<?php

declare(strict_types=1);

namespace App\Entity\Year2025\Day04;

use App\Entity\PathFinding\PositionInterface;

readonly class DomainLogic
{
    public function __construct(private array $map)
    {
    }

    public function getAdjacentNodes(Position $from): array
    {
        $adjacentNodes = [];
        [$startingRow, $endingRow, $startingColumn, $endingColumn] = $this->calculateAdjacentBoundaries($from);

        for ($row = $startingRow; $row <= $endingRow; $row++) {
            for ($column = $startingColumn; $column <= $endingColumn; $column++) {
                /** @var Position $adjacentNode */
                $adjacentNode = $this->map[$row][$column];

                if (!$adjacentNode->isPaperRoll) {
                    continue;
                }

                if (!$from->isEqualTo($adjacentNode)) {
                    $adjacentNodes[] = $adjacentNode;
                }
            }
        }

        return $adjacentNodes;
    }

    public function print(): void
    {
        foreach ($this->map as $row) {
            foreach ($row as $position) {
                echo $position->isPaperRoll ? '@' : '.';
            }

            echo PHP_EOL;
        }
    }

    private function calculateAdjacentBoundaries(PositionInterface $position): array
    {
        return [
            max(0, $position->row - 1),
            \count($this->map) - 1 === $position->row ? $position->row : $position->row + 1,
            max(0, $position->column - 1),
            \count($this->map[0]) - 1 === $position->column ? $position->column : $position->column + 1,
        ];
    }
}
