<?php

class DiffGenerator {
	private $originalString;
	private $originalChunks;
	
	private $newString;
	private $newChunks;
	
	private $breaker;
	
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
	}
}

?>