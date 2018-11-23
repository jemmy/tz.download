Clone repository

```bash
php composer install
```

Configure ```.env```. 
Set DB_DATABASE, DB_USERNAME, DB_PASSWORD and QUEUE_CONNECTION=database.

```bash
php artisan migrate
```
  
```bash  
php artisan queue:work
```     
  
Console commands

Download file from url 

```bash
php artisan download:add https://google.com
php artisan download:add https://www.google.com/logos/doodles/2018/nikolai-nosovs-110th-birthday-5902312345174016.5-l.png
```

Download files status

```bash
php artisan download:status
```

Api

Download files list

```bash
curl --request GET \
  --url 'https://{{host}}/downloads' 
```

Response 

```json
{
    "data": [
        {
            "id": 1,
            "status": {
                "code": 3,
                "name": "Complete"
            },
            "url": "https://google.com",
            "download": "/download/1"
        },
        {
            "id": 2,
            "status": {
                "code": 3,
                "name": "Complete"
            },
            "url": "https://www.google.com/logos/doodles/2018/nikolai-nosovs-110th-birthday-5902312345174016.5-l.png",
            "download": "/download/2"
        }
    ]
}
```

Download file from url 

```bash
curl --request POST \
  --url 'https://{{host}}/downloads'
  --header 'Content-Type: application/json' \
      --data '{
    	"url": "https://www.google.com"
    }' 
```

Response  

```json
{
    "data": {
        "id": 3
    },
    "message": "Job download https://www.google.com created."
}
```

Web

Download files list

```
    http://{host}/
```

Download file by id

```
    http://{host}/download/1 
    http://{host}/download/2 
```

