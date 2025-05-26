# Usage:
# . config-dump.sh

root=${PWD}
app=${root}/app

mkdir -p config-dump
cd ${app} || exit

php bin/console config:dump monolog >../config-dump/config-dump-monolog.yaml
php bin/console config:dump sensiolabs_gotenberg >../config-dump/config-dump-gotenberg.yaml
php bin/console config:dump security >../config-dump/config-dump-security.yaml
php bin/console config:dump framework >../config-dump/config-dump-framework.yaml

cd ${root} || exit
