<?php

class DiffGenerator {
	private $originalString;
	private $originalChunks;
	
	private $newString;
	private $newChunks;
	
	private $breaker;
	
	private $start;
	private $originalEnd;
	private $newEnd;
	
	private $lcsMatrix;
	
	private $diffs = array();
	private $diffsArrayPointer = 0;
	
	public function __construct($originalString = '', $newString = '') {
		$this->originalString = (string) $originalString;
		$this->newString = (string) $newString;
		
		$this->breaker = new StringBreaker();
	}
	
	public function addDelimiter($left = "", $right = "") {
		$this->breaker->addDelimiter($left, $right);
	}
	
	public function breakIntoEvenChunks($chunkSize = 1) {
		$this->breaker->setStringToBreak($this->originalString);
		$this->breaker->breakIntoEvenChunks($chunkSize);
		$this->originalChunks = $this->breaker->getChunkArray();
		
		$this->breaker->setStringToBreak($this->newString);
		$this->breaker->breakIntoEvenChunks($chunkSize);
		$this->newChunks = $this->breaker->getChunkArray();
	}
	
	public function breakIntoDelimitedChunks() {
		$this->breaker->setStringToBreak($this->originalString);
		$this->breaker->breakIntoDelimitedChunks();
		$this->originalChunks = $this->breaker->getChunkArray();
		
		$this->breaker->setStringToBreak($this->newString);
		$this->breaker->breakIntoDelimitedChunks();
		$this->newChunks = $this->breaker->getChunkArray();
	}
	
	public function calculateDiffs() {
		$this->findStartAndEndPositions();
		$this->initializeLCSMatrix();
		$this->calculateLCSMatrix();
		$this->addExcludedDiffsToStart();
		$this->traceThroughLCSMatrix($this->originalEnd - $this->start + 1, $this->newEnd - $this->start + 1);
		$this->addExcludedDiffsToEnd();
	}
	
	public function getDiffs() {
		return $this->diffs;
	}
	
	private function findStartAndEndPositions() {
		for(
			$this->start = 0;
			$this->start < min(count($this->originalChunks), count($this->newChunks)) && $this->originalChunks[$this->start]->getHash() == $this->newChunks[$this->start]->getHash();
			$this->start++
		);
		for(
			$this->originalEnd = count($this->originalChunks) - 1, $this->newEnd = count($this->newChunks) - 1;
			$this->start < $this->originalEnd && $this->start < $this->newEnd && $this->originalEnd > 0 && $this->newEnd > 0 && $this->originalChunks[$this->originalEnd]->getHash() == $this->newChunks[$this->newEnd]->getHash();
			$this->originalEnd--, $this->newEnd--
		);
	}
	
	private function initializeLCSMatrix() {
		for($row = 0; $row < ($this->originalEnd - $this->start) + 2; $row++) {
			for($column = 0; $column < ($this->newEnd - $this->start) + 2; $column++) {
				$this->lcsMatrix[$row][$column] = 0;
			}
		}
	}
	
	private function calculateLCSMatrix() {
		for($row = 1; $row < ($this->originalEnd - $this->start) + 2; $row++) {
			for($column = 1; $column < ($this->newEnd - $this->start) + 2; $column++) {
				$this->lcsMatrix[$row][$column] = ($this->originalChunks[$row + $this->start - 1]->getHash() == $this->newChunks[$column + $this->start - 1]->getHash()) ?
					$this->lcsMatrix[$row - 1][$column - 1] + 1 :
					max($this->lcsMatrix[$row][$column - 1], $this->lcsMatrix[$row - 1][$column]);
			}
		}
	}
	
	private function traceThroughLCSMatrix($row, $column) {
		if($row > 0 && $column > 0 && $this->originalChunks[$row + $this->start - 1]->getHash() == $this->newChunks[$column + $this->start - 1]->getHash()) {
			$this->traceThroughLCSMatrix($row - 1, $column - 1);
			$this->diffs[$this->diffsArrayPointer++] = new Diff($this->originalChunks[$row + $this->start - 1]->getChunk(), $row + $this->start - 1, $column + $this->start - 1);
		} else {
			if($column > 0 && ($row == 0 || $this->lcsMatrix[$row][$column - 1] >= $this->lcsMatrix[$row - 1][$column])) {
				$this->traceThroughLCSMatrix($row, $column - 1);
				$this->diffs[$this->diffsArrayPointer++] = new AddDiff($this->newChunks[$column + $this->start - 1]->getChunk(), $column + $this->start - 1);
			} elseif($row > 0 && ($column == 0 || $this->lcsMatrix[$row][$column - 1] < $this->lcsMatrix[$row - 1][$column])) {
				$this->traceThroughLCSMatrix($row - 1, $column);
				$this->diffs[$this->diffsArrayPointer++] = new DeleteDiff($this->originalChunks[$row + $this->start - 1]->getChunk(), $row + $this->start - 1);
			}
		}
	}
	
	private function addExcludedDiffsToStart() {
		for($currentChunk = 0; $currentChunk < $this->start; $currentChunk++) {
			$this->diffs[$this->diffsArrayPointer++] = new Diff($this->originalChunks[$currentChunk]->getChunk(), $currentChunk, $currentChunk);
		}
	}
	
	private function addExcludedDiffsToEnd() {
		for($currentOriginalChunk = $this->originalEnd + 1, $currentNewChunk = $this->newEnd + 1; $currentOriginalChunk < count($this->originalChunks); $currentOriginalChunk++, $currentNewChunk++) {
			$this->diffs[$this->diffsArrayPointer++] = new Diff($this->originalChunks[$currentOriginalChunk]->getChunk(), $currentOriginalChunk, $currentNewChunk);
		}
	}
}

?>