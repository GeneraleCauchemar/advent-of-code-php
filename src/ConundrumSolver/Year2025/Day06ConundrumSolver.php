<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;
use App\Service\MatrixHelper;

/**
 * ❄️ Day 6: Trash Compactor ❄️
 *
 * @see https://adventofcode.com/2025/day/6
 */
final class Day06ConundrumSolver extends AbstractConundrumSolver
{
    public function __construct()
    {
        parent::__construct('2025', '06');
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        $problems = $this->getInput();
        array_walk(
            $problems,
            static function (&$line) {
                $line = preg_split('/ +/', trim($line));
            },
        );

        MatrixHelper::rotateMatrix($problems);

        $result = 0;

        foreach ($problems as $problem) {
            $operator = trim(array_pop($problem));
            $result += $this->computeResult($problem, $operator);
        }

        return $result;
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        $problems = $this->getInput();
        $operators = preg_split('/ +/', array_pop($problems));
        array_walk(
            $problems,
            static function (&$line) {
                $line = str_split($line);
            }
        );

        $result = 0;
        $lineLength = \count(max($problems));
        $pKey = 0;
        $pOperands = [];

        for ($i = 0; $i < $lineLength; $i++) {
            $operandParts = array_column($problems, $i);
            $operand = array_filter($operandParts, static fn($part) => ' ' !== $part);

            // Not on a separator column: locking the operand
            if (!empty($operand)) {
                $pOperands[] = (int) implode('', $operand);

                // If we're on the last column, we still want to compute the result
                if ($i !== $lineLength - 1) {
                    continue;
                }
            }

            $result += $this->computeResult($pOperands, $operators[$pKey++]);
            $pOperands = [];
        }

        return $result;
    }

    // //////////////
    // METHODS
    // //////////////

    private function computeResult(array $operands, string $operator): int
    {
        $operands = array_map('\intval', $operands);

        return match ($operator) {
            '+' => array_sum($operands),
            '*' => array_product($operands),
        };
    }
}
