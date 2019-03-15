rm -rf var/cache/de*
rm -rf var/cahe/pr*
#rm -rf composer.lock
#rm -rf vendor
composer install
composer dump-autoload -o

php bin/console doctrine:migration:migrate -n

bower install

npm install

gulp --production

php bin/console assets:install
php bin/console assetic:dump
php bin/console cache:clear

# Update Search Index for Properties and Page
php bin/console ckcms:fcr:property:search-index
php bin/console ckcms:fcr:page:search-index
php bin/console ckcms:fcr:press-release:search-index
php bin/console ckcms:fcr:blog:search-index
php bin/console ckcms:fcr:job-position:search-index
php bin/console ckcms:fcr:people:search-index
php bin/console ckcms:fcr:faq:search-index

npm run webpack-serverside
npm run webpack-client
npm run webpack-bb
