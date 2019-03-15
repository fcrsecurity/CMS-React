# Fix Folder permissions
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var

# Build Core
composer install
composer dump-autoload -o

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate -n
php bin/console doctrine:fixtures:load -n

# Build Front-End
bower install

npm install

gulp --production

php bin/console assets:install
php bin/console assetic:dump
php bin/console cache:clear

npm run webpack-serverside