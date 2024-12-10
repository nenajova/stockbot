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
curl -X POST http://127.0.0.1:8000/api/stock -H "Con-Type: application/json" -d '{"name": "AAPL", "startDate": "01/07/2020", "endDate": "01/10/2020"}'
```

### Invoke-WebRequest test 
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


### Import data from .csv files
The 'data' is a folder name where csv files are stored. You can import it from other path.
```sh
php bin/console app:import-csv data
```