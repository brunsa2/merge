<?php

class StringBreaker {
	private $stringToBreak = '';
	
	private $delimiters = array();
	private $delimitersArrayPointer = 0;
	
	private $chunks = array();
	private $chunksArrayPointer = 0;
	
	public function __construct() {
	}
	
	public function setStringToBreak($stringToBreak = '') {
		$this->stringToBreak = (string) $stringToBreak;
		
		$this->chunks = array();
		$this->chunksArrayPointer = 0;
	}
	
	public function breakIntoEvenChunks($chunkSize = 1) {
		$stringPointer = 0;
		
		for($currentChunk = 0; $currentChunk < $this->getNumberOfChunks($chunkSize); $currentChunk++) {
			$chunk = new Chunk(substr($this->stringToBreak, $stringPointer, $chunkSize), $currentChunk, $stringPointer, $stringPointer + strlen(substr($this->stringToBreak, $stringPointer, $chunkSize)) - 1);
			$stringPointer += $chunkSize;
			$this->chunks[$this->chunksArrayPointer++] = $chunk;
		}
	}
	
	public function addDelimiter($left = "", $right = "") {
		$this->delimiters[$this->delimitersArrayPointer]['left'] = substr($left, 0, 1);
		$this->delimiters[$this->delimitersArrayPointer++]['right'] = substr($right, 0, 1);
	}
	
	public function breakIntoDelimitedChunks() {
		$stringPointer = 0;
		$currentPositionInOriginalString = 0;
		$currentChunkNumber = 0;
		
		while(strlen($this->stringToBreak) > 0) {
			for($stringPointer = 0; !$this->match(substr($this->stringToBreak, $stringPointer, $stringPointer + 1)) && $stringPointer < strlen($this->stringToBreak) - 1; $stringPointer++);
			
			$chunk = new Chunk(substr($this->stringToBreak, 0, $stringPointer + 1), $currentChunkNumber++, $currentPositionInOriginalString, $currentPositionInOriginalString + $stringPointer);
			$this->chunks[$this->chunksArrayPointer++] = $chunk;
			
			$this->stringToBreak = substr($this->stringToBreak, $stringPointer + 1);
			$currentPositionInOriginalString += $stringPointer + 1;
		}
	}
	
	public function getChunkArray() {
		return $this->chunks;
	}
	
	private function getNumberOfChunks($chunkSize) {
		return ceil(strlen($this->stringToBreak) / $chunkSize);
	}
	
	private function match($substring) {
		foreach($this->delimiters as $delimiter) {			
			if(($delimiter['left'] == "" || $delimiter['left'] == substr($substring, 0, 1)) && ($delimiter['right'] == "" || $delimiter['right'] == substr($substring, 1, 1))) {
				return true;
			}
		}
		
		return false;
	}
}

?>