<?php

namespace Allegro\Console;

use Allegro\Console\OutputInterface;

class Output implements OutputInterface
{

	public function writeln($messages)
	{
		$messages = (array) $messages;
		foreach ($messages as $message) {
			echo $message."\n";
		} 
	}

	public function write($messages)
	{
		echo $messages;
	}
	
}
