<?php declare(strict_types = 1);

namespace Luky\Console;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class FancyProgressBar
{
	protected const DEFAULT_STEP = 1;
	
	protected const COLOR_YELLOW = 'yellow';
	protected const COLOR_GREEN = 'green';
	
	/**
	 * @var \Symfony\Component\Console\Helper\ProgressBar
	 */
	private $progressBar;
	/**
	 * @var \Symfony\Component\Console\Output\OutputInterface
	 */
	private $output;
	/**
	 * @var float
	 */
	private $timer;
	
	public function __construct(\Symfony\Component\Console\Output\OutputInterface $output)
	{
		$this->output = $output;
		
		$output->getFormatter()->setStyle(self::COLOR_YELLOW, new OutputFormatterStyle(self::COLOR_YELLOW, 'default', []));
		$output->getFormatter()->setStyle(self::COLOR_GREEN, new OutputFormatterStyle(self::COLOR_GREEN, 'default', ['bold']));
		
		
		$this->progressBar = new ProgressBar($output);
		
		$this->progressBar->setMessage('', 'info');
	
		$this->progressBar->setFormat($this->buildFormat());
		$this->progressBar->setRedrawFrequency(10);
	
		$this->timer = 0 - \microtime(true);
	}
	
	public function getEstimatedTime(): string
	{
		return \number_format(round($this->timer + \microtime(true), 3), 3);
	}
	
	public function advanceWithMessage(string $message, int $step = self::DEFAULT_STEP): void
	{
		$this->progressBar->setMessage($message, 'info');
		$this->advance($step);
	}
	
	public function advanceWithPersisentMessage(string $message, int $step = self::DEFAULT_STEP): void
	{
		$this->advance($step);
		$this->output->writeln($message);
	}
	
	public function advance(int $step = self::DEFAULT_STEP): void
	{
		$this->progressBar->setMessage($this->getEstimatedTime(), 'timer');
		$this->progressBar->advance($step);
	}
	
	public function setOverwrite(bool $overwrite): void
	{
		$this->progressBar->setOverwrite($overwrite);
	}
	
	public function setProgress(int $step): void
	{
		
		$this->progressBar->setProgress($step);
	}
	
	public function setMaxSteps(int $max): void
	{
		$this->progressBar->setMaxSteps($max);
	}
	
	public function finish(): void
	{
		$this->progressBar->setFormat($this->buildFormat(self::COLOR_GREEN));
		$this->progressBar->finish();
		$this->progressBar->display();
	}
	
	protected function buildFormat(string $timer = self::COLOR_YELLOW): string
	{
		return "<$timer>%timer:6s%s</$timer> %current%/%max% [%bar%] %percent:3s%% %info%";
	}
}
