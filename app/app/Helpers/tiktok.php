<?php

if (!function_exists('make_url_tiktok_by_unique_user_and_video_id')) {
    /**
     * Make url tiktok by unique user and video id
     * @param string $unique_user
     * @param int $video_id
     * @param string $sub_domain
     * @return string
     */
    function make_url_tiktok_by_unique_user_and_video_id(string $unique_user, int $video_id, string $sub_domain = 'www'): string
    {
        return 'https://' . $sub_domain . '.tiktok.com/@' . $unique_user . '/video/' . $video_id;
    }
}

if (!function_exists('make_url_user_tiktok')) {
    /**
     * Make url user tiktok simple
     * @param string $unique_user
     * @param string $sub_domain
     * @return string
     */
    function make_url_user_tiktok(string $unique_user, string $sub_domain = 'www'): string
    {
        return 'https://' . $sub_domain . '.tiktok.com/@' . $unique_user;
    }
}

if (!function_exists('make_url_music_tiktok')) {
    /**
     * Make url music tiktok simple
     * @param string $title
     * @param string $music_id
     * @param string $sub_domain
     * @return string
     */
    function make_url_music_tiktok(string $title, string $music_id, string $sub_domain = 'www'): string
    {
        return 'https://' . $sub_domain . '.tiktok.com/music/' . $title . '-' . $music_id;
    }
}

if (!function_exists('get_id_video_by_url')) {
    /**
     * Get video id by url tiktok
     * @param string $tiktok_url
     * @return string
     */
    function get_id_video_by_url(string $tiktok_url): string
    {
        $path = parse_url($tiktok_url, PHP_URL_PATH);
        $path_arr = explode('/', $path);
        return $path_arr[count($path_arr) - 1];
    }
}

if (!function_exists('make_url_hashtag_tiktok')) {
    /**
     * Make url hashtag tiktok
     * @param string $hashtag_name
     * @param string $sub_domain
     * @return string
     */
    function make_url_hashtag_tiktok(string $hashtag_name, string $sub_domain = 'www'): string
    {
        return 'https://' . $sub_domain . '.tiktok.com/tag/' . $hashtag_name;
    }
}

if (!function_exists('generate_tiktok_device_id')) {
    /**
     * Generate tiktok device id
     * @returns string
     * */
    function generate_tiktok_device_id(): string
    {
        return random_number(digits: 19);
    }
}

if (!function_exists('random_number')) {
    /**
     * Random number
     *
     * @param int $digits
     * @return string
     */
    function random_number(int $digits = 8): string
    {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $digits; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}

if (!function_exists('get_id_music_by_url_tiktok')) {
    /**
     * Get id music by url tiktok
     *
     * @param string $url_music
     * @return string
     */
    function get_id_music_by_url_tiktok(string $url_music): string
    {
        try {
            $array = explode('-', $url_music);
            return $array[array_key_last($array)];
        } catch (\Exception $exception) {
            \Log::error($exception);
        }
        return "";
    }
}
