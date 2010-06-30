<?php

class StringBreaker {
	private $stringToBreak = '';
	private $chunks = array();
	private $chunksArrayPointer = 0;
	
	public function __construct($stringToBreak = '') {
		$this->stringToBreak = (string) $stringToBreak;
	}
	
	public function breakIntoEvenChunks($chunkSize = 1) {
		$stringPointer = 0;
		
		for($currentChunk = 0; $currentChunk < $this->getNumberOfChunks($chunkSize); $currentChunk++) {
			$chunk = new Chunk(substr($this->stringToBreak, $stringPointer, $chunkSize), $currentChunk, $stringPointer, $stringPointer + $chunkSize - 1);
			$stringPointer += $chunkSize;
			$this->chunks[$this->chunksArrayPointer++] = $chunk;
		}
	}
	
	public function getChunkArray() {
		return $this->chunks;
	}
	
	private function getNumberOfChunks($chunkSize) {
		return ceil(strlen($this->stringToBreak) / $chunkSize);
	}
}

?>