Subsmag  (в контейнерах [docker](https://www.docker.com/))
==============

Проекты Subsmag могут быть запущены в контейнерах на локальном компьютере.
Проекты обращаются к одной базе данных, которая должна быть стартована на локальном компьютере до их запуска.

<br>

----------------------------------------------------

#### Требуемое ПО
На компьютере разработчика должно быть установлены:
* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [db-docker](https://git.rg.ru/ivlev/db-docker) - База данных MySQL для всех приложений outer.

    Установка и запуск db-docker

    ```sh
    git clone git@git.rg.ru:ivlev/db-docker.git
    cd db-docker
    docker-compose up -d
    ```

--------------------------------------------------

## Порядок работы 

1. Из папки `subsmag/` запустите сервисы docker

    ```sh
    docker-compose up -d
    ```
    Просмотр логов:
    ```sh
    docker-compose logs -f
    ```


    Очистка кэша Synfony:
    ```sh
    docker-compose exec subs /var/www/subsmag/www/bin/console cache:clear --no-warmup --env=prod
    ```

    Для контроля запуска откройте браузер :

    * [http://localhost:3032](http://localhost:3032/)
    * [http://localhost:3032/api/](http://localhost:3032/api/)  должен показать []
    * [http://localhost:3032/api/products/](http://localhost:3032/api/products/)  JSON продуктов
    <!-- * [http://localhost:3032/subs/](http://localhost:3032/subs/) Фронтэнд. -->


2. Измените и оттестируйте код.
3. Внесите изменения в репозиторий:
    ```sh
    git pull
    git add -A .
    git commit -m "..."
    git push
    ```
---------------------






<br>
<br>

**Команды**

Временная остановка сервисов 

```sh
docker-compose stop
```

Пуск остановленных сервисов

```sh
docker-compose start
```

Останов и удаление сервисов

```sh
docker-compose down
```

Стартованные сервисы выдерживают перезагрузку компьютера.

<br>
<br>


**Oтладка PHP**



1. Узнайте локальный IP своего компьютера :

    ```sh
    sudo ifconfig | grep 'inet 192'
    ```

2. В файле  `etc/php/php.ini`. Установите параметр
 `remote_host` в значение своего IP :

    ```sh
    ...
    xdebug.remote_host=192.168.0.1 # your IP
    ...
    ```
3. Перезапустите сервисы
   ```sh
   docker-compose restart
   ```

Документация об  [удаленной отладке Xdebug](https://xdebug.org/docs/remote). 
[Интеграция PHPStorm с docker](https://github.com/nanoninja/docker-nginx-php-mysql/blob/master/doc/phpstorm-macosx.md).


