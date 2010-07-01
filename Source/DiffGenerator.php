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
		
		
		foreach($this->originalChunks as $chunk) {
			echo $chunk . '<br />';
		}
		
		echo '<br />';
		
		foreach($this->newChunks as $chunk) {
			echo $chunk . '<br />';
		}
		
		echo '<br />';
	}
	
	public function breakIntoDelimitedChunks() {
		$this->breaker->setStringToBreak($this->originalString);
		$this->breaker->breakIntoDelimitedChunks();
		$this->originalChunks = $this->breaker->getChunkArray();
		
		$this->breaker->setStringToBreak($this->newString);
		$this->breaker->breakIntoDelimitedChunks();
		$this->newChunks = $this->breaker->getChunkArray();
		
		foreach($this->originalChunks as $chunk) {
			echo $chunk . '<br />';
		}
		
		echo '<br />';
		
		foreach($this->newChunks as $chunk) {
			echo $chunk . '<br />';
		}
		
		echo '<br />';
	}
	
	public function calculateDiffs() {
		$this->findStartAndEndPositions();
		$this->initializeLCSMatrix();
		$this->calculateLCSMatrix();
		
		$currentLine = '';
		
		for($row = 0; $row < ($this->originalEnd - $this->start) + 2; $row++) {
			for($column = 0; $column < ($this->newEnd - $this->start) + 2; $column++) {
				$currentLine .= $this->lcsMatrix[$row][$column] . ' ';
			}
			
			echo $currentLine . '<br />';
			$currentLine = '';
		}
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
		
		echo 'Start: ' . $this->start . '<br />';
		echo 'Original end: ' . $this->originalEnd . '<br />';
		echo 'New end: ' . $this->newEnd . '<br />';
		echo '<br />';
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
}

?>