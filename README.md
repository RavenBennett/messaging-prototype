Set up redis server: https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/

```
composer install 
npm install
```


Copy the .env.example file to .env: 
```
cp .env.example .env
php artisan key:generate
```


Run migrations and seed
```
php artisan migrate --seed 
```

Run Reverb and workers
```
php artisan reverb:star 
php artisan queue:work
```

Start site
```
npm run dev
```
