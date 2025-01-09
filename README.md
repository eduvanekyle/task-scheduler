
## Installation and Deployment

Create a .env file based on the .env.example.

```bash
  cp .env.example .env
```

Configure the Database variables needed.

Run Composer
```bash
  composer install
```

After composer, run the necessary migrations
```bash
  php artisan migrate
```

Generate an APP_KEY
```bash
  php artisan key:generate
```

Install node packages
```bash
  npm install
```

To deploy locally, run
```bash
  npm run dev
```

Serve the application
```bash
  php artisan serve
```
