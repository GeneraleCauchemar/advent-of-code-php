<?php

namespace App\Entity\Year2022\Day12;

use JMGQ\AStar\AStar as BaseAStar;
use JMGQ\AStar\DomainLogicInterface;
use JMGQ\AStar\Node\Collection\NodeCollectionInterface;
use JMGQ\AStar\Node\Collection\NodeHashTable;
use JMGQ\AStar\Node\Node;

class AStar extends BaseAStar
{
    private DomainLogicInterface $domainLogic;
    private NodeCollectionInterface $openList;
    private NodeCollectionInterface $closedList;

    public function __construct(DomainLogicInterface $domainLogic)
    {
        parent::__construct($domainLogic);

        $this->domainLogic = $domainLogic;
        $this->openList = new NodeHashTable();
        $this->closedList = new NodeHashTable();
    }

    public function run(mixed $start, mixed $goal): iterable
    {
        $startNode = new Node($start);
        $goalNode = new Node($goal);

        return $this->executeAlgorithm($startNode, $goalNode);
    }

    private function executeAlgorithm(Node $start, Node $goal): iterable
    {
        $path = [];

        $this->clear();

        $start->setG(0);
        $start->setH($this->calculateEstimatedCost($start, $goal));

        $this->openList->add($start);

        while (!$this->openList->isEmpty()) {
            $currentNode = $this->openList->extractBest();

            $this->closedList->add($currentNode);

            if (1 === $currentNode->getState()->getZ()) {
                $path = $this->generatePathFromStartNodeTo($currentNode);
                break;
            }

            $successors = $this->getAdjacentNodesWithTentativeScore($currentNode, $goal);

            $this->evaluateSuccessors($successors, $currentNode);
        }

        return $path;
    }

    /**
     * Sets the algorithm to its initial state
     */
    private function clear(): void
    {
        $this->openList->clear();
        $this->closedList->clear();
    }

    private function generateAdjacentNodes(Node $node): iterable
    {
        $adjacentNodes = [];

        $adjacentStates = $this->domainLogic->getAdjacentNodes($node->getState());

        foreach ($adjacentStates as $state) {
            $adjacentNodes[] = new Node($state);
        }

        return $adjacentNodes;
    }

    private function calculateRealCost(Node $node, Node $adjacent): float | int
    {
        $state = $node->getState();
        $adjacentState = $adjacent->getState();

        return $this->domainLogic->calculateRealCost($state, $adjacentState);
    }

    private function calculateEstimatedCost(Node $start, Node $end): float | int
    {
        $startState = $start->getState();
        $endState = $end->getState();

        return $this->domainLogic->calculateEstimatedCost($startState, $endState);
    }

    private function generatePathFromStartNodeTo(Node $node): iterable
    {
        $path = [];

        $currentNode = $node;

        while ($currentNode !== null) {
            array_unshift($path, $currentNode->getState());

            $currentNode = $currentNode->getParent();
        }

        return $path;
    }

    private function getAdjacentNodesWithTentativeScore(Node $node, Node $goal): iterable
    {
        $nodes = $this->generateAdjacentNodes($node);

        foreach ($nodes as $adjacentNode) {
            $adjacentNode->setG($node->getG() + $this->calculateRealCost($node, $adjacentNode));
            $adjacentNode->setH($this->calculateEstimatedCost($adjacentNode, $goal));
        }

        return $nodes;
    }

    private function evaluateSuccessors(iterable $successors, Node $parent): void
    {
        foreach ($successors as $successor) {
            if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->openList)) {
                continue;
            }

            if ($this->nodeAlreadyPresentInListWithBetterOrSameRealCost($successor, $this->closedList)) {
                continue;
            }

            $successor->setParent($parent);

            $this->closedList->remove($successor);

            $this->openList->add($successor);
        }
    }

    private function nodeAlreadyPresentInListWithBetterOrSameRealCost(
        Node $node,
        NodeCollectionInterface $nodeList
    ): bool {
        if ($nodeList->contains($node)) {
            $nodeInList = $nodeList->get($node->getId());

            if ($node->getG() >= $nodeInList->getG()) {
                return true;
            }
        }

        return false;
    }
}
