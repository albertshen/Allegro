<?php

namespace Allegro\Console;

interface OutputInterface
{
	public function writeln($messages);

	public function write($messages);
}
