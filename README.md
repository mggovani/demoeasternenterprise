## Version History 

### Laravel version 8.0

### PHP version ^7.3


## Project Setup Instruction

### Clone project using 'git clone' command

### Install composer in project directory

``` bash
composer install
``` 

### Make a file named '.env' and copy '.env.example' file data into '.env' and add database details to it

### Migrate tables to database

``` bash
php artisan migrate
```

### Execute project

``` bash
php artisan serve
```


## Use Below APIs For Responses

### For import json file data use below get api url

- http://127.0.0.1:8000/api/v1/import-data

### For get companies with users and filters use below get api with parameters

- http://127.0.0.1:8000/api/v1/companies

- OPTIONAL PARAMETERS
	- **min_age**
	- **max_age**
	- **year**

- For pagination use **page** and **per_page** parameters