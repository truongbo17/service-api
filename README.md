# Main Service


### Config run service
- Download and enable FFmpeg.
  - Exam : `ffmpeg -i input.mp4 -vf "scale=ih:iw/2,setsar=1,pad=0:ih*2" output.mp4`

- Build Serve MinIO (Storage File).

- Make video with event listeners queue (don't use with QUEUE_CONNECTION=sync)
  - Run `php artisan queue:listen --queue=make_video --tries=0 --timeout=0` | Queue listen make video

- Set `max_execution_time = 1000` in `php.ini`

----------------
### Signature Tiktok Service
- Github source : https://github.com/pablouser1/SignTok

```text
docker pull ghcr.io/pablouser1/signtok:master
docker run --publish 8080:8080 ghcr.io/pablouser1/signtok:master
```
- Set .env `ENDPOINT_TIKTOK_SIGNATURE=http://127.0.0.1:8080`
