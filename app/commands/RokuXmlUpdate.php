<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \Illuminate\Support\Facades\Queue;

class RokuXmlUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'roku:updateXml';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
	    $channel_id = intval($this->argument('channel_id'));
		Queue::push(\App\Jobs\Roku\UpdateXml::REBUILD_CHANNEL, ['channel_id' => $channel_id]);
		$this->line('Added to queue: rebuild channel '.$channel_id);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('channel_id', InputArgument::REQUIRED, 'Channel ID'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('channel_id', null, InputOption::VALUE_REQUIRED, 'Channel ID', null),
		);
	}

}
