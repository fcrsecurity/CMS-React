set SYMFONY_ENV_BACK=%SYMFONY_ENV%
set SYMFONY_ENV=test
rm -rf var/cache/_*
rm -rf var/cache/te*
rem cmd /c composer install --no-ansi
rem cmd /c composer dump-autoload -o --no-ansi
cmd /c php bin/console doctrine:database:drop --force --no-ansi
cmd /c php bin/console doctrine:database:create --no-ansi
rem cmd /c php bin/console doctrine:migration:migrate -n --no-ansi
cmd /c php bin/console doctrine:schema:update --force --no-ansi
cmd /c php bin/console doctrine:fixtures:load -n --no-ansi

cmd /c npm install
cmd /c bower install
cmd /c gulp --production

cmd /c php bin/console assets:install --no-ansi
cmd /c php bin/console assetic:dump --no-ansi

cmd /c npm run webpack-serverside
set SYMFONY_ENV=%SYMFONY_ENV_BACK%
