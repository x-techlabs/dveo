<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Aws\S3\S3Client;

class s3 extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 's3';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deleting videos.';

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
		$s3 = S3Client::factory([
			'key' => 'AKIAIC7KBJ3BO3TEQJ3Q',
			'secret' => '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',
		]);

		$objects = $s3->getIterator('ListObjects', [
			'Bucket' => 'prolivestream',
			'Prefix' => 'videos',
		]);

//		$s3->deleteObject(array(
//			'Bucket' => 'prolivestream',
//			'Key' => 'videos/test.jpg.jpg',
//		));

		$channel_storage = [];

		foreach($objects as $object) {

			$filename = $object['Key'];

			$video = Video::where('file_name', '=', pathinfo(explode('/', $filename)[1], PATHINFO_FILENAME))->first();

			if($video) {
				$video->storage = $object['Size'];
				$video->save();

				if (empty($channel_storage[$video->channel_id])) $channel_storage[$video->channel_id] = 0;

				$channel_storage[$video->channel_id] += $video->storage;
			}
			else {
				$this->info($filename);
			}
		}

		$channel_ids = array_keys($channel_storage);

		$all_channels = Channel::all();

		foreach ($all_channels as $channel) {
			$channel->storage = 0;

			if (in_array($channel->id, $channel_ids)) {
				$channel->storage = $channel_storage[$channel->id];
			}

			$channel->save();
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
//		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
//		);

		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
//		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
//		);

		return [];
	}

}
