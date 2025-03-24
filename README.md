Set up redis server: https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/
and make sure its running.

```
composer install 
npm install
```


Copy the .env.example file to .env
and update any variables:
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

Make sure its running as https
If you want to open both the angular and laravel app at the same time then either use two diffrent browsers or use one with private browsing

go to /admin and
login with
Email:
```
admin@example.com
```
Password:
```
secret
```
