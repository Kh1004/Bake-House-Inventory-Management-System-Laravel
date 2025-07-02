@echo off
echo Starting database setup...

:: Run migrations
echo.
echo Running migrations...
php artisan migrate:fresh --force

:: Run database seeders
echo.
echo Seeding database...
php artisan db:seed --force

:: Run alert seeders
echo.
echo Seeding alert configurations...
php artisan db:seed --class=AlertDemoSeeder --force
php artisan db:seed --class=TestAlertSeeder --force

echo.
echo Database setup completed successfully!
pause
