# Usage:
# . install.sh

function install_contributing_env() {
    root=${PWD}

    echo
    echo "Creating a new test app project"
    echo "--------------------------------"

    symfony check:requirements

    symfony new app --version=7.2
    cd ${root}/app

    composer require symfony/http-client     # Avoid cache:clear error: HttpClient support cannot be enabled as the component is not installed
    composer require symfony/security-bundle # Source of inspiration
    composer require symfony/workflow        # Source of inspiration

    echo
    echo "Get the Symfony Source Code"
    echo "---------------------------"

    cd ${root}
    git clone git@github.com:symfony/symfony.git --branch 7.2 --depth 1

    echo
    echo "# Links dependencies of the app project to the local clones"
    echo

    cd ${root}/symfony
    php link ${root}/app

    echo
    echo "Get the GotenbergBundle Source Code (Jean-Beru fork)"
    echo "----------------------------------------------------"

    #git clone git@github.com:gotenberg/gotenberg.git
    cd ${root}
    git clone git@github.com:Jean-Beru/GotenbergBundle.git --branch 1.x

    echo
    echo "# Links dependencies of the app project to the local clones"
    echo

    cd ${root}/app
    # See https://getcomposer.org/doc/05-repositories.md#using-private-repositories
    composer config repositories.gotenberg-bundle path ../GotenbergBundle
    composer require sensiolabs/gotenberg-bundle:@dev

    echo
    echo "Get the MonologBundle Source Code (jprivet-dev fork)"
    echo "----------------------------------------------------"

    cd ${root}
    git clone git@github.com:jprivet-dev/monolog-bundle.git --branch monolog-bundle-dynamic-config-node

    echo
    echo "# Links dependencies of the app project to the local clones"
    echo

    cd ${root}/app
    # See https://getcomposer.org/doc/05-repositories.md#using-private-repositories
    composer config repositories.monolog-bundle path ../monolog-bundle
    composer require symfony/monolog-bundle:@dev

    echo
    echo "Get the Nelmio/Alice Source Code"
    echo "--------------------------------"

    cd ${root}
    git clone git@github.com:nelmio/alice.git

    echo
    echo "# Links dependencies of the app project to the local clones"
    echo

    cd ${root}/app
    # See https://getcomposer.org/doc/05-repositories.md#using-private-repositories
    composer config repositories.alice path ../alice
    composer require nelmio/alice:@dev

    echo
    echo "Get the PocBundle Source Code"
    echo "-----------------------------"

    echo
    echo "# Links dependencies of the app project to the local bundle"
    echo

    cd ${root}/app
    # See https://getcomposer.org/doc/05-repositories.md#using-private-repositories
    composer config repositories.poc-bundle path ../poc-bundle
    composer require local/poc-bundle:@dev

    echo
    echo "Utils"
    echo "-----"

    composer require --dev symfony/maker-bundle
    composer require orm
    composer require --dev symfony/profiler-pack

    echo
    echo "Start a Symfony Local Web Server"
    echo "--------------------------------"

    cd ${root}
    symfony server:start --dir=app --daemon
}

install_contributing_env
