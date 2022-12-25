# Service Get Data From API Tiktok or Thirdly Part

_(Working with PHP8.1)_

- **Importation** Set .env `AUTH_TOKEN_API=your_token` this code will authenticate api requests, only this one will work.
- Set cookie in `config/tiktok.php` with key `tiktok_cookie`
---------------
### Proxy Rotation

- Github source package : https://github.com/truongbo17/proxy-rotator
- Set .env `USE_PROXY_ROTATION=true` if you want to use a revolving proxy when making requests to the api.
- Setup proxy in App/Providers/AppServiceProvider.php method handleProxy().

----------------
### Signature Tiktok Service
- Github source : https://github.com/pablouser1/SignTok

```text
docker pull ghcr.io/pablouser1/signtok:master
docker run --publish 8080:8080 ghcr.io/pablouser1/signtok:master
```
- Set .env `ENDPOINT_TIKTOK_SIGNATURE=http://127.0.0.1:8080`

### API Document
