<?php

/**
 * Created by PhpStorm.
 * User: Aramayis
 * Date: 9/10/14
 * Time: 7:47 PM
 */
class Time
{

    /**
     * Change the data in seconds to human data
     *
     * @param $arr
     * @return mixed
     */
    public static function change_to_human_data_in_array($arr)
    {
		
        foreach ($arr as $key => $val) {
            $arr[$key]['time'] = Time::change_to_human_data_in_object($val);
        }
        return $arr;
    }

    /**
     * Change the data in seconds to human data
     *
     * @param $obj
     * @return time
     */
    public static function change_to_human_data_in_object($obj)
    {
        $seconds = (int)$obj['duration'];
        $hours = (($h = floor($seconds / 60)) < 60) ? 0 : floor($h / 60);
        $minutes = floor(($seconds - ($hours * 3600)) / 60);
        $second = $seconds - $minutes * 60 - $hours * 3600;

        $hours = ($hours < 10) ? '0' . $hours . '' : $hours;
        $minutes = ($minutes < 10) ? '0' . $minutes . '' : $minutes;
        $second = ($second < 10) ? '0' . $second . '' : $second;

        $time = $hours . ':' . $minutes . ':' . $second;

        return $time;
    }

    /**
     * Add the current time seconds and milliseconds
     *
     * @param $seconds
     * @param int $milliseconds
     * @return int
     */
    public static function  add_seconds($seconds = 0, $milliseconds = 0)
    {
        return ((time() + $seconds) * 1000) + $milliseconds;
    }

    /**
     * @param int $seconds
     * @param int $milliseconds
     * @return int
     */
    public static function minus_time($seconds = 0, $milliseconds = 0)
    {
        return ((time() - $seconds) * 1000) - $milliseconds;
    }
} 