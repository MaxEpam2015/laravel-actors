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
composer install
```

### NPM / Vite installation:
```bash
npm install
```

### Copy .env file:
```bash
docker exec -it actor_php-fpm cp -v .env.example .env
```

### Provide your OPENAI_API_KEY in .env
```
#.env
OPENAI_API_KEY=...
```

### Database migrations:
```bash
docker exec -it actor_php-fpm php artisan migrate
```

### Run Vite dev server:
```bash

docker exec -it astro_node npm run build
```

### Run tests:
```bash
docker exec -it actor_php-fpm php artisan test

```
