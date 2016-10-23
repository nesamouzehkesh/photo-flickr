sudo composer install
sudo chmod -R 777 ./app/config/parameters.yml
sudo chmod -R 777 ./var/logs
sudo chmod -R 777 ./var/cache
sudo chmod -R 777 ./var/logs/dev.log

sudo php bin/console doctrine:schema:update --force
sudo php bin/console fixture:generateData

#bower install

#sudo php bin/console cache:clear --no-debug
#sudo php bin/console assets:install --symlink --relative
#sudo php bin/console assetic:dump --no-debug
#sudo php bin/console assetic:watch