<?php

class UnifiedDiffs {
	private $diffs;
	
	private $originalFileName;
	private $newfileName;
	private $originalTimetamp;
	private $newTimestamp;
	
	private $contextLines;
	
	private $regionMap = array();
	
	private $regions = array();
	private $regionsArrayPointer = 0;
	
	public function __construct(array $diffs, $originalFileName = '', $newFileName = '', $originalTimestamp = '', $newTimestamp = '', $contextLines = 1) {
		$this->diffs = $diffs;
		
		$this->originalFileName = $originalFileName;
		$this->newfileName = $newFileName;
		
		$this->originalTimetamp = $originalTimestamp;
		$this->newTimestamp = $newTimestamp;
		
		$this->contextLines = $contextLines >= 1 ? $contextLines : 1;
		
		for($currentChunk = 0; $currentChunk < count($this->diffs); $currentChunk++) {
			$this->regionMap[$currentChunk] = 0;
		}
		
		for($currentChunk = 0; $currentChunk < count($this->diffs); $currentChunk++) {
			if($this->diffs[$currentChunk] instanceof AddDiff || $this->diffs[$currentChunk] instanceof DeleteDiff) {
				$this->regionMap[$currentChunk] = 1;
				
				for($currentRegion = max($currentChunk - $this->contextLines, 0); $currentRegion < $currentChunk; $currentRegion++) {
					$this->regionMap[$currentRegion] = 1;
				}
				
				for($currentRegion = min($currentChunk + $this->contextLines, count($this->diffs)); $currentRegion > $currentChunk; $currentRegion--) {
					$this->regionMap[$currentRegion] = 1;
				}
			}
		}
		
		$region = new Region();
		
		for($currentRegion = 0; $currentRegion < count($this->diffs); $currentRegion++) {
			if($this->regionMap[$currentRegion] == 1) {
				$region->addDiff($this->diffs[$currentRegion]);
			} else {
				if(!$region->isEmpty()) {
					$this->regions[$this->regionsArrayPointer++] = $region;
					$region = new Region();
				}
			}
		}
		
		if(!$region->isEmpty()) {
			$this->regions[$this->regionsArrayPointer++] = $region;
		}
	}
	
	public function __toString() {
		$unifiedDiffString = '--- ' . $this->originalFileName . ' ' . $this->originalTimetamp . "\n" . '+++ ' . $this->newfileName . ' ' . $this->newTimestamp . "\n";
		
		foreach($this->regions as $region) {
			$unifiedDiffString .= ($region . "\n");
		}
		
		return $unifiedDiffString;
	}
}

?>