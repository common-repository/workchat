<?php

if (!class_exists('Cloodo_Workchat')) {
class Cloodo_Workchat
    {
        public function __construct()
        {
        }

        public static function get_employees()
        {
            $employee = get_users(array('role__not_in' => 'Administrator'));
            return $employee;
        }

        // get token
        public static function get_token($token){
            $response = wp_remote_get(CLOODO_BASE_API . 'companies', array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                )
            ));

            if (is_wp_error($response)) {
                return 'Error: ' . $response->get_error_message();
            }

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['data']) && !empty($data['data'])) {
                $x_token = $data['data'][0]['x_token']; // Giả sử x_token là một trường dữ liệu trong phản hồi 
                //var_dump($x_token);
                return $x_token;
            } else {
                return 'No data found';
            }
        }
    }
}