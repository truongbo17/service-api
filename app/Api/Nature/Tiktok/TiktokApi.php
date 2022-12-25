<?php

namespace App\Api\Nature\Tiktok;

use App\RequestTikTok\CURL;
use App\RequestTikTok\Query;
use App\RequestTikTok\Requests;
use App\RequestTikTok\UserAgents;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Exception\GuzzleException;
use Log;

class TiktokApi
{
    /**
     * @var string $host
     * Host api
     * */
    private string $host = "https://tiktok.com";

    /**
     * @var string $host_mobile
     * Host api mobile
     * */
    private string $host_mobile = "https://m.tiktok.com";

    /**
     * @var string $host
     * Host api tiktokv
     * */
    private string $host_api = "https://api-h2.tiktokv.com";

    /**
     * @var string $path_api_get_trending
     * Path api get trending
     * */
    private string $path_api_get_trending = "api/recommend/item_list";

    /**
     * @var string $path_api_no_watermark
     * Path api get video no watermark
     * */
    private string $path_api_no_watermark = "aweme/v1/feed/";

    /**
     * @var string $path_api_get_video_by_hashtag
     * path api get video by hashtag name
     * */
    private string $path_api_get_video_by_hashtag = "api/challenge/item_list";

    /**
     * @var string $path_api_get_video_by_music
     * path api get video by music id
     * */
    private string $path_api_get_video_by_music = "api/music/item_list";

    /**
     * @var string $path_api_get_video_by_user
     * path api get video by user unique
     * */
    private string $path_api_get_video_by_user = "api/post/item_list";

    /**
     * @var int $aid
     * aid by tiktok api
     * */
    private int $aid_tiktok = 1988;

    /**
     * Construct class TiktokApi
     * @param ClientInterface $client
     * @param CookieJarInterface $cookie
     */
    public function __construct(
        private readonly ClientInterface    $client,
        private readonly CookieJarInterface $cookie)
    {

    }

    /**
     * Get trending video tiktok
     *
     * @param string $method
     * @param int $count
     * @param int $cursor
     *
     * @return array
     * @throws GuzzleException
     */
    public function getTrending(string $method, int $count, int $cursor = 0): array
    {
        try {
            $query_trending = [
                'aid'    => $this->aid_tiktok,
                'count'  => $count,
                'cursor' => $cursor,
            ];
            $endpoint = build_external_url(host: $this->host, path: $this->path_api_get_trending, query: $query_trending);
            $response = $this->client->request($method, $endpoint, ['cookies' => $this->cookie]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);
            if (isset($data['itemList']) && is_array($data['itemList'])) {
                foreach ($data['itemList'] as $key => $video) {
                    $_video_info['video_id'] = $video['id'];
                    $_video_info['wmplay'] = $video['video']['downloadAddr'];
                    $_video_info['region'] = '';
                    $_video_info['duration'] = $video['video']['duration'];
                    $_video_info['title'] = $video['desc'];
                    $_video_info['play_count'] = $video['stats']['playCount'];
                    $_video_info['digg_count'] = $video['stats']['diggCount'];
                    $_video_info['comment_count'] = $video['stats']['commentCount'];
                    $_video_info['share_count'] = $video['stats']['shareCount'];
                    $_video_info['download_count'] = 0;
                    $_video_info['create_time'] = $video['createTime'];
                    $_video_info['music_info'] = $video['music'];
                    $_video_info['author'] = $video['author'];

                    $data['itemList'][$key] = $_video_info;
                }
                return $data['itemList'];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get video no watermark and has watermark
     *
     * @param string $method
     * @param string $tiktok_url
     * @return array
     * @throws GuzzleException
     */
    public function getVideoDownload(string $method, string $tiktok_url): array
    {
        $video_id = get_id_video_by_url($tiktok_url);
        try {
            $query_no_watermark = Query::queryNoWaterMarkVideo(video_id: $video_id);

            $endpoint = build_external_url(host: $this->host_api, path: $this->path_api_no_watermark, query: $query_no_watermark);

            $response = $this->client->request($method, $endpoint, [
                'headers' => [
                    'User-Agent' => UserAgents::DOWNLOAD->get(),
                ]
            ]);
            $contents = json_decode($response->getBody()->getContents(), true);

            if (isset($contents['aweme_list'][0])) {
                $_video_info = [];

                $_video_info['id'] = $contents['aweme_list'][0]['aweme_id'] ?? "";
                $_video_info['region'] = $contents['aweme_list'][0]['region'] ?? "";
                $_video_info['title'] = $contents['aweme_list'][0]['desc'] ?? "";
                $_video_info['cover'] = $contents['aweme_list'][0]['video']['dynamic_cover']['url_list'][0] ?? "";
                $_video_info['origin_cover'] = $contents['aweme_list'][0]['video']['origin_cover']['url_list'][0] ?? "";
                $_video_info['duration'] = ($contents['aweme_list'][0]['duration'] ?? 0) / 1000;
                $_video_info['play'] = $contents['aweme_list'][0]['video']['play_addr']['url_list'] ?? "";
                $_video_info['wmplay'] = $contents['aweme_list'][0]['video']['download_addr']['url_list'] ?? "";
                $_video_info['hdplay'] = $contents['aweme_list'][0]['video']['play_addr']['url_list'] ?? "";
                $_video_info['music'] = $contents['aweme_list'][0]['music']['play_url']['url_list'][0] ?? "";
                $_video_info['music_info'] = $contents['aweme_list'][0]['music'] ?? [];
                $_video_info['play_count'] = $contents['aweme_list'][0]['statistics']['play_count'] ?? 0;
                $_video_info['digg_count'] = $contents['aweme_list'][0]['statistics']['digg_count'] ?? 0;
                $_video_info['comment_count'] = $contents['aweme_list'][0]['statistics']['comment_count'] ?? 0;
                $_video_info['share_count'] = $contents['aweme_list'][0]['statistics']['share_count'] ?? 0;
                $_video_info['download_count'] = $contents['aweme_list'][0]['statistics']['download_count'] ?? 0;
                $_video_info['create_time'] = $contents['aweme_list'][0]['create_time'] ?? 0;
                $_video_info['author'] = $contents['aweme_list'][0]['author'] ?? [];

                return $_video_info;
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get info tiktok by SIGI_STATE
     *
     * @param string $tiktok_url
     * @param array $query
     * @return array
     * @throws GuzzleException
     */
    public function getInfoSigiState(string $tiktok_url, array $query = []): array
    {
        try {
            $tiktok_url = build_external_url(host: $tiktok_url, path: null, query: $query);
            $response = $this->client->get($tiktok_url, [
                'headers' => [
                    'User-Agent' => UserAgents::DEFAULT->get()
                ]
            ]);
            $html = $response->getBody()->getContents();
            $dom = new \DomDocument();
            @$dom->loadHTML($html);
            $script = $dom->getElementById('SIGI_STATE');
            if ($script) {
                return json_decode($script->textContent, true);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get Video List By Challenge(HashTag)
     * @param string $method
     * @param string $challenge_name
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideoByHashTag(string $method, string $challenge_name, int $count = 10, int $cursor = 0): array
    {
        try {
            $hashtag_url = make_url_hashtag_tiktok(hashtag_name: $challenge_name);
            $challenge_id = $this->getInfoSigiState(tiktok_url: $hashtag_url)['ChallengePage']['challengeInfo']['challenge']['id'];

            $device_id = generate_tiktok_device_id();
            $query = [
                "count"       => $count,
                "challengeID" => $challenge_id,
                "cursor"      => $cursor,
                "device_id"   => $device_id,
            ];
            $query = Query::queryBasicTiktok(query: $query);

            $endpoint = build_external_url(host: $this->host_mobile, path: $this->path_api_get_video_by_hashtag, query: $query);

            $response = Requests::send(
                client: $this->client,
                endpoint: $endpoint,
                path: $this->path_api_get_video_by_hashtag,
                device_id: $device_id,
                method: $method
            );

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return [
                    'videos'  => $data['itemList'],
                    'cursor'  => (int)$data['cursor'],
                    'hasMore' => $data['hasMore'],
                ];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }

    /**
     * Get music feed videos
     * @param string $method
     * @param string $music_id
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideosByMusicId(string $method, string $music_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $device_id = generate_tiktok_device_id();
            $query = [
                "secUid"   => "",
                "musicID"  => $music_id,
                "cursor"   => $cursor,
                "shareUid" => "",
                "count"    => $count,
            ];
            $query = Query::queryBasicTiktok(query: $query);

            $endpoint = build_external_url(host: $this->host_mobile, path: $this->path_api_get_video_by_music, query: $query);

            $response = Requests::send(
                client: $this->client,
                endpoint: $endpoint,
                path: $this->path_api_get_video_by_music,
                device_id: $device_id,
                method: $method
            );

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return [
                    'videos'  => $data['itemList'],
                    'cursor'  => (int)$data['cursor'],
                    'hasMore' => $data['hasMore'],
                ];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }


    /**
     * Get music feed videos
     * @param string $method
     * @param string $unique_id
     * @param int $count
     * @param int $cursor
     * @return array
     * @throws GuzzleException
     */
    public function getVideosByUser(string $method, string $unique_id, int $count = 10, int $cursor = 0): array
    {
        try {
            $user_url = make_url_user_tiktok(unique_user: $unique_id);

            $html_user_profile = CURL::sendHTML(url: $user_url);

            preg_match('/\"authorSecId\":\"\w+\"/', $html_user_profile, $matches);
            $author_sec_id = explode(":", $matches[0])[1];
            $author_sec_id = str_replace("\"", "", $author_sec_id);

            preg_match('/\"authorId\":\"[0-9]+\"/', $html_user_profile, $matches);
            $user_id = explode(":", $matches[0])[1];
            $user_id = str_replace("\"", "", $user_id);

            $device_id = generate_tiktok_device_id();
            $query = [
                "count"      => 30,
                "id"         => $user_id,
                "cursor"     => $cursor,
                "type"       => 1,
                "secUid"     => $author_sec_id,
                "sourceType" => 8,
                "appId"      => 1233
            ];
            $query = Query::queryBasicTiktok(query: $query);

            $endpoint = build_external_url(host: $this->host_mobile, path: $this->path_api_get_video_by_user, query: $query);

            $response = Requests::send(
                client: $this->client,
                endpoint: $endpoint,
                path: $this->path_api_get_video_by_user,
                device_id: $device_id,
                method: $method
            );

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return [
                    'videos'  => $data['itemList'],
                    'cursor'  => (int)$data['cursor'],
                    'hasMore' => $data['hasMore'],
                ];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [];
    }
}
