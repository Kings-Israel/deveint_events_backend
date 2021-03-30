### Project Setup

git clone https://github.com/Kings-Israel/deveint_events_backend.git

### Run the commands

### Generate application key

php application key:generate

### Setup up auth with laravel ui and vue

composer require laravel/ui
php artisan ui vue --auth
npm install
npm run dev

### Setup api auth with sanctum

composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

### Domain setup

Setup the app to use the domain events.test as this is the backend to app.events.test
