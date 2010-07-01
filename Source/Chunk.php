<?php

class Chunk {
	private $chunk = '';
	private $hash;
	private $chunkNumber;
	private $startPosition;
	private $endPosition;
	
	public function __construct($chunk = '', $chunkNumber = 0, $startPosition = 0, $endPosition = 0) {
		$this->chunk = (string) $chunk;
		$this->hash = hexdec(substr(sha1($this->chunk), 0, 8));
		$this->chunkNumber = $chunkNumber;
		$this->startPosition = $startPosition;
		$this->endPosition = $endPosition;
	}
	
	public function __toString() {
		$stringRepresentation = $this->chunk;
		$stringRepresentation .= ' (' . $this->hash . '; start: ' . $this->startPosition;
		$stringRepresentation .= '; end: ' . $this->endPosition . '; number: ' . $this->chunkNumber . ')';
		return $stringRepresentation;
	}
	
	public function getChunk() {
		return $this->chunk;
	}
	
	public function getHash() {
		return $this->hash;
	}
	
	public function getStartPosition() {
		return $this->startPosition;
	}
	
	public function getEndPosition() {
		return $this->endPosition;
	}
}

?>