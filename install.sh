# Usage
# . install.sh

printf "\n\n"
printf "Get the Symfony Source Code\n"
printf "---------------------------\n"

git clone git@github.com:jprivet-dev/symfony.git --branch symfony-monolog-bundle-poc
cd symfony

printf "\n\n"
printf "Automatic tests\n"
printf "---------------\n"

composer update
php ./phpunit src/Symfony/Bridge/Monolog

printf "\n\n"
printf "Creating a new test app project\n"
printf "--------------------------------\n"

cd ..
symfony check:requirements
symfony new app --version=7.2
cd app
composer require symfony/monolog-bundle

printf "\n\n"
printf "Use the symfony-monolog-bundle-poc branch in the new app project\n"
printf "----------------------------------------------------------------\n"

cd ../symfony
php link ../app

printf "\n\n"
printf "Go back to the racine\n"
printf "---------------------\n"

cd ..

printf "\n\n"
printf "Start a Symfony Local Web Server\n"
printf "--------------------------------\n"

symfony server:start --dir=app --daemon
