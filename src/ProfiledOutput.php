<?php declare(strict_types = 1);

namespace Luky\Console;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProfiledOutput extends ConsoleOutput
{
	protected $timer;
	
	public function __construct(
		int $verbosity = self::VERBOSITY_NORMAL,
		bool $decorated = null,
		OutputFormatterInterface $formatter = null
	)
	{
		parent::__construct($verbosity, $decorated, $formatter);

		$this->timer = 0 - \microtime(true);
	}
	

	public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
	{
		if (is_iterable($messages)) {
			$messages = \array_map([$this, 'profileMessage'], $messages);
		} else {
			$messages = $this->profileMessage($messages);
		}
		
		
		parent::write($messages, $newline, $options);
	}
	
	protected function profileMessage($message)
	{
		$x = $this->timer + \microtime(true);
		
		return '['
			   . \str_pad((string) $x, 10, '0')
			   . ']'
			   . $message;
	}
}
