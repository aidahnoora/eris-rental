## Laravel

1. Navigate in your Laravel API project folder: `cd your-laravel-json-api-project`
2. Install project dependencies: `composer install`
3. Create a new .env file: `cp .env.example .env`
4. Add your own database credentials in the .env file in DB_DATABASE, DB_USERNAME, DB_PASSWORD
5. Create users table: `php artisan migrate --seed`
6. Generate application key: `php artisan key:generate`
7. Install Laravel Passport: php artisan passport:install and set in the .env file the CLIENT_ID and CLIENT_SECRET that you receive
8. Add your own mailtrap.io credentials in MAIL_USERNAME and MAIL_PASSWORD in the .env file

## Vue

1. Navigate to your Vue Dashboard project folder: `cd your-vue-argon-dashbord-project`
2. Install project dependencies: `npm install`
3. Create a new .env file: `cp .env.example .env`
4. `VUE_APP_BASE_URL` should contain the URL of your Vue Argon Dashboard Project (eg. http://localhost:8080/)
5. `VUE_APP_API_BASE_URL` should contain the URL of your Laravel JSON:API Project. (eg. http://localhost:3000/api/v1)
6. Run `npm run dev` to start the application in a local development environment or `npm run build` to build release distributables.