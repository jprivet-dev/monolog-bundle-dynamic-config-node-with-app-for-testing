# Usage
# . scripts/install.sh

set +x -e

# Get the Symfony Source Code
git clone git@github.com:jprivet-dev/symfony.git --branch symfony-monolog-bundle-poc
cd symfony

# Automatic tests
composer update
php ./phpunit src/Symfony/Bridge/Monolog

# Creating a new test `app` project
cd ..
symfony check:requirements
symfony new app --version=7.2
cd app
composer require symfony/monolog-bundle

# Use the branch `jprivet-dev:symfony-monolog-bundle-poc` in the new `app` project
cd ../symfony
php link ../app
