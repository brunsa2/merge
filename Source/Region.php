<?php

class Region {
	private $diffs = array();
	private $diffsArrayPointer = 0;
	
	public function addDiff(Diff $diff) {
		$this->diffs[$this->diffsArrayPointer++] = $diff;
	}
	
	public function isEmpty() {
		return $this->diffsArrayPointer == 0;
	}
	
	public function __toString() {
		$foundFirstOriginalChunkNumber = false;
		$foundFirstNewChunkNumber = false;
		
		$firstOriginalChunkNumber = 0;
		$firstNewChunkNumber = 0;
		
		$index = 0;
		
		while(!($foundFirstOriginalChunkNumber && $foundFirstNewChunkNumber) && $index < count($this->diffs)) {
			if(!$foundFirstOriginalChunkNumber && $this->diffs[$index]->getOriginalChunkNumber() >= 0) {
				$firstOriginalChunkNumber = $this->diffs[$index]->getOriginalChunkNumber();
				$foundFirstOriginalChunkNumber = true;
			}
			
			if(!$foundFirstNewChunkNumber && $this->diffs[$index]->getNewChunkNumber() >= 0) {
				$firstNewChunkNumber = $this->diffs[$index]->getNewChunkNumber();
				$foundFirstNewChunkNumber = true;
			}
			
			$index++;
		}
		
		$foundLastOriginalChunkNumber = false;
		$foundLastNewChunkNumber = false;
		
		$lastOriginalChunkNumber = 0;
		$lastNewChunkNumber = 0;
		
		$index = count($this->diffs) - 1;
		
		while(!($foundLastOriginalChunkNumber && $foundLastNewChunkNumber) && $index > 0) {
			if(!$foundLastOriginalChunkNumber && $this->diffs[$index]->getOriginalChunkNumber() >= 0) {
				$lastOriginalChunkNumber = $this->diffs[$index]->getOriginalChunkNumber();
				$foundLastOriginalChunkNumber = true;
			}
			
			if(!$foundLastNewChunkNumber && $this->diffs[$index]->getNewChunkNumber() >= 0) {
				$lastNewChunkNumber = $this->diffs[$index]->getNewChunkNumber();
				$foundLastNewChunkNumber = true;
			}
			
			$index--;
		}
		
		$originalStart = $firstOriginalChunkNumber + 1;
		$newStart = $firstNewChunkNumber + 1;
		
		$originalLength = $lastOriginalChunkNumber - $firstOriginalChunkNumber + 1;
		$newLength = $lastNewChunkNumber - $firstNewChunkNumber + 1;
		
		$regionAsString = '@@ -' . $originalStart . ($originalLength > 1 ? (',' . $originalLength) : '') . ' +' . $newStart . ($newLength > 1 ? (',' . $newLength) : '') . ' @@' . "\n";
		
		foreach($this->diffs as $diff) {
			if($diff instanceof AddDiff) {
				$regionAsString .= ('+' . $diff->getChunk() . "\n");
			} elseif($diff instanceof DeleteDiff) {
				$regionAsString .= ('-' . $diff->getChunk() . "\n");
			} else {
				$regionAsString .= (' ' . $diff->getChunk() . "\n");
			}
		}
		
		$regionAsString = substr($regionAsString, 0, strlen($regionAsString) - 1);
		
		return $regionAsString;
	}
}

?>