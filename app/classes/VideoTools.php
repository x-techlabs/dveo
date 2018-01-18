<?php


class VideoTools {

    /**
     * Channel name to publish to
     *
     * @var string
     */
    private $channel_name;

    /**
     * Redis connection
     *
     * @var Redis
     */
    private $redis;

    /**
     * Constructor of VideoTools class
     *
     * @param string $channel_name
     */
    public function __construct($channel_name) {

        $this->channel_name = $channel_name;
        $this->redis = Redis::connection();
    }

    /**
     * Creating thumbnail
     *
     * @param string       $file_name
     * @param string       $thumbnail_name
     * @param int          $time
     * @param Notification $notification
     */
    public function create_thumbnail($file_name, $thumbnail_name, $time, Notification $notification) {

        $this->send('create_thumbnail', $notification, [
            'file_name' => $file_name,
            'thumbnail_path' => $thumbnail_name,
            'time' => $time,
        ]);
    }

    /**
     * Sending request
     *
     * @param string       $action
     * @param Notification $notification
     * @param array        $data
     */
    private function send($action, Notification $notification, Array $data) {

        $data = array_merge([
            'action' => $action,
            'notifications' => $notification->get_as_array()
        ], $data);

        $this->redis->publish($this->channel_name, json_encode($data));
    }
} 