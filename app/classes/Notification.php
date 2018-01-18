<?php

class Notification {

    const HTTP = 'http';
    const WebSocket = 'websocket';

    /**
     * List of notifications
     *
     * @var array
     */
    private $notifications = [];

    /**
     * Adding notifications
     *
     * @param string $type
     * @param array  $data
     */
    public function add($type, $data) {

        $data = array_merge([
            'endpoint' => '',
            'user_id' => '',
            'key' => '',
        ], $data);

        $this->notifications[] = [
            'type' => $type,
            'endpoint' => $data['endpoint'],
            'user_id' => $data['user_id'],
            'key' => $data['key'],
        ];
    }

    /**
     * Get notifications as array
     *
     * @return array
     */
    public function get_as_array() {

        return $this->notifications;
    }
}
