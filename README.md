{"id":"69421","variant":"standard","title":"Laravel REST API README Actors/Directors/Movies"}
# Laravel REST API

We do _not_ use default `SPA Authentication` https://laravel.com/docs/11.x/sanctum#spa-authentication

## Endpoints

| URL           | HTTP method | Auth | JSON Response           |
| ------------- | ----------- | ---- | ---------------------- |
| /users/login  | POST        |      | user's token            |
| /users        | GET         | Y    | all users               |
| /actors       | GET         |      | all actors              |
| /actors       | POST        | Y    | new actor added         |
| /actors       | PATCH       | Y    | edited actor            |
| /actors       | DELETE      | Y    | id                      |
| /directors    | GET         |      | all directors           |
| /directors    | POST        | Y    | new director added      |
| /directors    | PATCH       | Y    | edited director         |
| /directors    | DELETE      | Y    | id                      |
| /movies       | GET         |      | all movies              |
| /movies       | POST        | Y    | new movie added         |
| /movies       | PATCH       | Y    | edited movie            |
| /movies       | DELETE      | Y    | id                      |

## Steps

1. `composer create-project laravel/laravel laravel-rest-api`
2. `cd laravel-rest-api`
3. `php artisan serve`
4. Edit `.env`, set up mysql database
5. `php artisan install:api` (if your package/setup requires it)
6. Change User seed && `php artisan db:seed`
7. `php artisan make:controller UsersController`
8. `php artisan make:controller ActorsController`
9. `php artisan make:controller DirectorsController`
10. `php artisan make:controller MoviesController`
11. `php artisan make:migration create_actors_table`
12. `php artisan make:migration create_directors_table`
13. `php artisan make:migration create_movies_table`
14. `php artisan migrate`
15. `php artisan make:request ActorRequest`
16. `php artisan make:request DirectorRequest`
17. `php artisan make:request MovieRequest`
18. `php artisan config:publish cors`
