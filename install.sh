# Usage
# . install.sh

echo
echo "Get the Symfony Source Code"
echo "---------------------------"

git clone git@github.com:jprivet-dev/symfony.git --branch symfony-monolog-bundle-poc
cd symfony

echo
echo "Automatic tests"
echo "---------------"

composer update
php ./phpunit src/Symfony/Bridge/Monolog

echo
echo "Creating a new test app project"
echo "--------------------------------"

cd ..
symfony check:requirements

symfony new app --version=7.2
cd app
composer require symfony/monolog-bundle

echo
echo "Use the symfony-monolog-bundle-poc branch in the new app project"
echo "----------------------------------------------------------------"

cd ../symfony
php link ../app

# Go back to the racine
cd ..

echo
echo "Start a Symfony Local Web Server"
echo "--------------------------------"

symfony server:start --dir=app --daemon
