# Build Core

rm -rf var/cache/de*
rm -rf var/cache/pr*
# rm -rf vendor/*

composer install

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate -n
php bin/console doctrine:fixtures:load -n

# Load Initial Data
php bin/console ckcms:fcr:import:financial-reports
php bin/console ckcms:fcr:import:press-release
php bin/console ckcms:fcr:import:leasing-properties
php bin/console ckcms:fcr:import:historical-data
php bin/console ckcms:fcr:pull-stock-values
php bin/console ckcms:fcr:import:dividends
php bin/console ckcms:fcr:import:analyst-coverage
# Load Position, this is require CURL
php bin/console ckcms:fcr:careers:pull-positions

# Build Front-End
bower install

npm install

gulp --production

php bin/console assets:install
php bin/console assetic:dump
php bin/console cache:clear

npm run webpack-serverside
npm run webpack-client
npm run webpack-bb
