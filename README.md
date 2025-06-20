# Laravel CRUD App

This is a simple crud app using laravel, created using `laravel new <name>`

## Requirements

- php
- Composer
- nodeJS

## Initialization

1. Duplicate `.env.example` file and rename it into `.env`

2. Uncomment the variables

    - DB_CONNECTION
    - DB_HOST
    - DB_PORT
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD

    note: I used `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=laravel-crud-app` and for `DB_USERNAME` and `DB_PASSWORD` use your credentials for it.

3. Create the database in the database service you use
   ex: `CREATE DATABSE IF NOT EXISTS \`laravel-crud-app\`;`

4. run: `npm install`

5. run: `php artisan migrate`

6. Finally run: `composer run dev`
