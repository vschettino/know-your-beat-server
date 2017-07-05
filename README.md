Know Your Beat (Server)
============================

This is the server-side of an app that helps you to understand your musical taste and find songs you may like. Buit with the [Spotify Web API](https://developer.spotify.com/web-api) and on top of  [Yii 2 Framework](http://www.yiiframework.com/). Many thanks to [@jwilsson](https://github.com/jwilsson/spotify-web-api-php/) and his wonderful Spotify API SDK for PHP.

REQUIREMENTS
------------

- PHP 7.x
- Redis
- Apache2

INSTALLATION
------------

You will need [Composer](http://getcomposer.org/) to fetch and install all dependencies through:

```composer install```

I recommend [Docker's image for Redis](https://hub.docker.com/_/redis/), that makes it easy to install and use. Simple pull the image and start it:
~~~
docker pull redis

sudo docker container run --name kyb-redis -d -p 6379:6379 -v /var/redis-data:/data redis redis-server --appendonly yes
~~~

Finally, you'll need to set up your account credentials for the [authentication flow](https://developer.spotify.com/web-api/authorization-guide/#authorization_code_flow). Once you're done, you can make a new file called ```config/spotify.php``` and set it up as:

~~~
<?php
return [
  'YOUR_CLIENT_ID',
  'YOUR_CLIENT_SECRET',
  'YOUR_REDIRECT_API'
];
~~~
For obvious security reasons, this configs should not be publicated, so this file is ignored by Git on ```.gitignore```.

INSTALLATION
------------

Want to contribute? Grate! First of all, please [create a new issue](https://github.com/vschettino/know-your-beat-server/issues/new).
