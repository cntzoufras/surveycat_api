<p align="center"><a href="https://laravel.com" target="_blank"><img src="public/app-logo.png" width="400"></a></p>

- [Powerful dependency injection container](https://laravel.com/docs/container).
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

## Installation

- Clone repository
- Run `php artisan sail:install` inside the project folder
- Select Postgres database, Redis & Memcached
- Run `sail up` inside the project folder
- Access the database using the following:
    - username: `sail`
    - password `password`
    - host `::1`
    - database: `surveycat`
- Add `alias sailbash='docker exec -it api-laravel.test-1 /bin/bash'` inside your `~/.bash_aliases`
- Run `source ~/.bash_aliases`
- Run `sailbash` for shell in dev container.
- Access `Telescope` dashboard in https://localhost/telescope
- Containers will start then can be inspected running using the `docker ps` command in the hosts bash
- To access the containers in Windows use the `Docker Desktop` executable or the `sailbash` command.

## License

The Surveycat app is licensed under the [MIT license](https://opensource.org/licenses/MIT).
