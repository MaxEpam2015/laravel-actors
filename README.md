# actors-app

```bash
git clone https://github.com/MaxEpam2015/laravel-actors.git
cd laravel-actors
```

## Building project:

```bash
docker compose up -d
```

### Composer installation:
```bash
docker exec -it actor_php-fpm composer install
```

### NPM / Vite installation:
```bash
docker exec -it astro_php-fpm npm install
```

### Copy .env file:
```bash
docker exec -it actor_php-fpm cp -v .env.example .env
```

### Database migrations:
```bash
docker exec -it actor_php-fpm php artisan migrate
```

### Run tests:
```bash
docker exec -it actor_php-fpm php artisan test

```

### Run Vite dev server:
```bash

docker exec -it astro_node npm run dev
```
