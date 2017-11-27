cd /home/forge/$ACACHA_FORGE_DOMAIN$
if [ -x ./pre-deploy.sh ]; then
  ./pre-deploy.sh
fi
git pull origin master
if [ -x ./post-deploy.sh ]; then
  ./post-deploy.sh
fi
echo "" | sudo -S service php7.1-fpm reload

if [ -x ./post-reload.sh ]; then
  ./post-reload.sh
fi