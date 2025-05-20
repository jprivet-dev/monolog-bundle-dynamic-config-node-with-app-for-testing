# Usage
# . install.sh

function install_contributing_env() {
  local path_root=$PWD
  local path_app=${path_root}/app
  local path_symfony=${path_root}/symfony

  echo
  echo "Get the Symfony Source Code"
  echo "---------------------------"

  git clone git@github.com:jprivet-dev/symfony.git --branch monolog-bundle-dynamic-config-node
  cd ${path_symfony} || exit

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
  cd app || exit

  composer require symfony/monolog-bundle      # Feature's main subject
  composer require symfony/security-bundle     # Source of inspiration
  composer require sensiolabs/gotenberg-bundle # Source of inspiration
  composer require symfony/http-client         # Avoid cache:clear error: HttpClient support cannot be enabled as the component is not installed

  echo
  echo "Use the monolog-bundle-dynamic-config-node branch in the new app project"
  echo "------------------------------------------------------------------------"

  cd "${path_symfony}" || exit
  php link "${path_app}"

  echo
  echo "Start a Symfony Local Web Server"
  echo "--------------------------------"

  cd "${path_root}" || exit
  symfony server:start --dir=${path_app} --daemon
}

install_contributing_env
