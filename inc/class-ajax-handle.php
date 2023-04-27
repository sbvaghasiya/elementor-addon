<?php

if (!defined('ABSPATH')) {
    die();
}
if (!class_exists('EVOLT_Ajax_Handle')) {
    class EVOLT_Ajax_Handle {
        public function __construct() {
            add_action('wp_ajax_evolt_auto_generate', array($this, 'evolt_auto_generate'));
        }

        function evolt_auto_generate(){
            try {
                $result = [
                    'stt' => true,
                    'msg' => __('Generate Successfully!', EVOLT_TEXT_DOMAIN),
                    'data' => strtoupper(substr(md5(uniqid(mt_rand(), true) . ':' . microtime(true)), 5, 11)),
                ];
                wp_send_json($result);
            } catch (Exception $e) {
                $result = [
                    'stt' => false,
                    'msg' => $e->getMessage(),
                    'data' => '',
                ];
                wp_send_json($result);
            }
            die();
        }
    }
    new EVOLT_Ajax_Handle();
}