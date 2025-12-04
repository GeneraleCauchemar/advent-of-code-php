<?php

namespace App\Entity\Year2025\Day04;

use App\Entity\PathFinding\AbstractPosition;

class Position extends AbstractPosition
{
    public function __construct(public int $row, public int $column, public bool $isPaperRoll)
    {
        parent::__construct($row, $column);
    }
}
