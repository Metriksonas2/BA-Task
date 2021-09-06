# Phone Book Documentation

## About project

Technologies used for the project:

* Front end - **CSS** and **Javascript** (jQuery)

* Back end - **PHP** (Symfony)

---

## What is this application?

This is a simple CRUD type phone book application. You are able to create own phone book records and you can share them with other people as well. There **"Shared Records"** for checking out records that other people are sharing with you.

Suggestion for testing **sharing** function: open second browser (or same browser's incognito tab) and login with 2 different users. Try sharing records with each other.

## How to prepare application?

To build Docker Containers: 

* ```shell
    docker-compose up -d
  ```
  
After building Docker containers:
  
* ```shell
    cd ./application
  ```
  
* ```shell
    composer install
    npm install
  ```
  
To build Webpack:

* ```shell
    npm run build
  ```

Automatically set Webpack to watch changes:

* ```shell
    npm run watch
  ```
  
Make sure to run all the migrations:

* ```shell
    php bin/console doctrine:migrations:migrate
  ```

If there are any issues with Docker Apache server, make sure to enable .htaccess file in ``application/public`` by renaming `htaccess` to `.htaccess` (or use `symfony server:start` command to use Symfony's built-in server instead).