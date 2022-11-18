## Event APP

#### Copy .env.example to .env
```
cp .env.example .env
```

#### Install Packages for backend

```
composer install
```

#### Migrate database and seed the data
```
php artisan migrate --seed
```

#### Install Packages for frontend and build assets for dev 

```
npm install
npm run dev
```

#### Serve laravel project

```
php artisan server
```
