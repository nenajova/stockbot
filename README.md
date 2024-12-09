# StockBot
### Run project
Symfony (port:8000)
```sh
symfony server:start
```
Docker (port:8080)
```sh
docker compose up
```

### CURL test
```sh
Invoke-WebRequest -Uri http://127.0.0.1:8000/api/stock `
>>     -Method POST `
>>     -Headers @{ "Content-Type" = "application/json" } `
>>     -Body '{"name": "Company Stock Name", "startDate": "2024-01-01", "endDate": "2024-12-31"}'
```

```sh
Invoke-WebRequest -Uri http://127.0.0.1:8080/api/stock `
>>     -Method POST `
>>     -Headers @{ "Content-Type" = "application/json" } `
>>     -Body '{"name": "Company Stock Name", "startDate": "2024-01-01", "endDate": "2024-12-31"}'
```