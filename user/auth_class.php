<?php

class HandleAuth
{

    private function test_auth_expiration($ini)
    {
        $url = "https://api.twitch.tv/helix/streams";
        $ch = curl_init($url);
        $headers = array();
        $headers[] = 'client-id:' . $ini["DEFAULT"]['client_id'];
        $headers[] = 'authorization:' . $ini["DEFAULT"]['authorization'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $test_req = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return $http_status;
    }

    private function refresh_token($ini)
    {
        $refresh_url = "https://id.twitch.tv/oauth2/token";
        $ch = curl_init($refresh_url);
        $headers = array();
        $headers[] = 'Content-Type=application/x-www-form-urlencoded';
        $data = array('client_id' => $ini["DEFAULT"]['client_id'], 'client_secret' => $ini["DEFAULT"]['client_secret'], 'grant_type' => $ini["DEFAULT"]['grant_type']);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $refresh_req = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $json = json_decode($refresh_req, true);
        if ($http_status !== 200 || $json['access_token'] === null) {
            curl_close($ch);
            return;
        }
        curl_close($ch);
        $this->refresh_conf(__DIR__ . '/../../cfg.ini', "DEFAULT", "authorization", 'Bearer ' . $json['access_token']);
        date_default_timezone_set('Europe/Helsinki');
        $date = date('Y-m-d H:i:s');
        $expires = $date+$json['expires_in'];
        $expires_date = date('Y-m-d H:i:s', $expires);
        $this->refresh_conf(__DIR__ . '/../../cfg.ini', "UPDATED", "date", 'Bearer ' . $date);
        $this->refresh_conf(__DIR__ . '/../../cfg.ini', "UPDATED", "expires", $expires_date);
        return;
    }

    private function refresh_conf($config_file, $section, $key, $value) {
        $config_data = parse_ini_file($config_file, true);
        $config_data[$section][$key] = $value;
        $new_content = '';
        foreach ($config_data as $section => $section_content) {
            $section_content = array_map(function($value, $key) {
                return "$key=$value";
            }, array_values($section_content), array_keys($section_content));
            $section_content = implode("\n", $section_content);
            $new_content .= "[$section]\n$section_content\n";
        }
        file_put_contents($config_file, $new_content);
    }

    public function execute_token_check()
    {
        $ini = parse_ini_file(__DIR__ . '/../../cfg.ini', true);
        $http_status = $this->test_auth_expiration($ini);
        if ($http_status !== 200) {
            $this->refresh_token($ini);
        } else {
            return;
        }
    }
}

?>

