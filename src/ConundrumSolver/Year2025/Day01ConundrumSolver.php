<?php

namespace App\ConundrumSolver\Year2025;

use App\ConundrumSolver\AbstractConundrumSolver;

/**
 * ❄️ Day 1: Secret Entrance ❄️
 *
 * @see https://adventofcode.com/2025/day/1
 */
final class Day01ConundrumSolver extends AbstractConundrumSolver
{
    private const int NEUTRAL = 0;
    private const int STARTING_POINT = 50;
    private const int DIAL_LENGTH = 100;
    private const string LEFT = 'L';

    private int $stoppedOnNeutral = 0;
    private int $pointedAtNeutral = 0;

    public function __construct()
    {
        parent::__construct('2025', '01');
    }

    #[\Override]
    public function warmup(): void
    {
        $pointer = self::STARTING_POINT;

        foreach ($this->getInput() as $instruction) {
            [$direction, $steps] = preg_split('/(?<=^[LR])/', $instruction, 2);

            $this->updatePointer($pointer, $direction, (int) $steps);
        }
    }

    // //////////////
    // PART 1
    // //////////////

    public function partOne(): string|int
    {
        return $this->stoppedOnNeutral;
    }

    // //////////////
    // PART 2
    // //////////////

    public function partTwo(): string|int
    {
        return $this->pointedAtNeutral;
    }

    // //////////////
    // METHODS
    // //////////////

    private function updatePointer(int &$pointer, string $direction, int $steps): void
    {
        $nextPosition = self::LEFT === $direction ? $pointer - $steps : $pointer + $steps;

        /**
         * Modulo handling where negative numbers are possible, to ensure
         * a result in the range [0, DIAL_LENGTH)
         */
        $moveTo = ($nextPosition % self::DIAL_LENGTH + self::DIAL_LENGTH) % self::DIAL_LENGTH;
        if (self::NEUTRAL === $moveTo) {
            $this->stoppedOnNeutral++;
        }

        /**
         * How many full rotations were done + negative movement passing through zero
         */
        $rotations = intdiv(abs($nextPosition), self::DIAL_LENGTH)
            + ((0 >= $nextPosition && 0 !== $pointer) ? 1 : 0);
        $this->pointedAtNeutral += $rotations;

        $pointer = $moveTo;
    }
}
