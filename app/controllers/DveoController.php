<?php
class DveoController extends BaseController {

    public function __construct() {
        //$this->dveo = new DVEO(BaseController::get_dveo_ip(), 25599, 'apiuser', 'Hn7P67583N9m5sS');
    }

    public function start() {
        $res = BaseController::get_dveo()->start_stream(BaseController::get_file_stream());
        return Response::json([
            'res' => BaseController::get_file_stream()
        ]);
    }

    public function stop() {
        BaseController::get_dveo()->stop_stream(BaseController::get_file_stream());
    }

    public function status() {
        $status = BaseController::get_dveo()->get_stream_status(BaseController::get_file_stream());

        if(strpos($status->GetStreamStatusResult, 'up') !== false) {
            return true;
        } else {
            return false;
        }
    }
}