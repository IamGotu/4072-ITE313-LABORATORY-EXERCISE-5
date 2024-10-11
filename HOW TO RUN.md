HOW TO RUN LARAVEL (social_media_app)

Download the code from https://github.com/IamGotu/4072-ITE313-LABORATORY-EXERCISE-5

If xampp is not installed download it here https://www.apachefriends.org/index.html and install it (skipped if already downloaded/installed)

Open the xampp and start the module Apache and MySQL

If MySQL module is not starting, open Task Manager

End task mysqld, then start the MySQL module again

Install NodeJS here: https://nodejs.org/en

In the terminal located in folder social_media_app type "npm install" to have node module

Enable the zip extension in PHP. Go to xampp find Config(aligned to the module Apache) press it to open PHP (ini.php). Then change ;extension=zip to extension=zip

Navigate to your project folder: example, C:\xampp\htdocs\4072-ITE313-LABORATORY-EXERCISE-5\social_media_app.

Right-click the folder and choose Properties.

Go to the "Security" tab and ensure that your user account has the appropriate permissions (read, write, modify).

If necessary, edit the permissions to allow full control for your user account.

Click Edit, select your user, and check Full Control. Click Apply and then OK to save the changes.

In the terminal located in folder social_media_app type "composer install" to have vendor

In the terminal located in folder social_media_app type "composer require pusher/pusher-php-server" and npm install --save laravel-echo pusher-js

Inside folder social_media_app find the file ".env.example" and change it to ".env"

In the terminal located in folder social_media_app type "php artisan migrate" to migrate the database

In the terminal located in folder social_media_app type "npm run dev"

In the terminal located in folder social_media_app type "php artisan serve"

If it says no application encryption key has been specified

In the terminal located in folder social_media_app type "php artisan key:generate"

Then try again in the terminal located in folder social_media_app the "php artisan serve"

Open the running server or Ctrl+click the following link