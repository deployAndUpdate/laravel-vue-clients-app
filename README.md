Микро-гайд для запуска и установки
git init
git remote add origin https://github.com/deployAndUpdate/laravel-vue-clients-app
git pull origin main
composer install    
copy .env.example .env    
php artisan generate:key
npm install     
php artisan migrate
php artisan db:seed
php artisan serve
npm run dev

Удачного тестирования!
