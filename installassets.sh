# Build Front-End
bower install

gulp --production

php bin/console assets:install
php bin/console assetic:dump
php bin/console cache:clear
