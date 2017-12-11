<?php
class TimerEntry {
	public function __construct($timestamp) {
		$this->timestamp = $timestamp;
	}
	public function getTime() {
			return date('H:i', $this->timestamp);
	}
	public function getElapsed() {
			return $this->timestamp;
	}
}

class Timer {
    
	public function __construct($log_filename) {
		$this->log_filename = $log_filename;
	}

	public function add() {
		file_put_contents($this->log_filename, time() . "\n", FILE_APPEND);
	}

	/**
	 * @return TimerEntry[] 
	 */
	public function getTimers() {
		$entries = [];
		$input = file($this->log_filename);
		rsort($input);
		$input = array_slice($input, 0, 4);
		foreach($input as $line) {
			$entries[] = new TimerEntry($line);
		}
		return $entries;
	}
}

