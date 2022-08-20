
docker build -t oniki/php-queue .
docker tag oniki/php-queue:latest 520762770364.dkr.ecr.eu-central-1.amazonaws.com/oniki/php-queue:latest
docker push 520762770364.dkr.ecr.eu-central-1.amazonaws.com/oniki/php-queue:latest

docker build -t oniki/php-fpm .
docker tag oniki/php-fpm:latest 520762770364.dkr.ecr.eu-central-1.amazonaws.com/oniki/php-fpm:latest
docker push 520762770364.dkr.ecr.eu-central-1.amazonaws.com/oniki/php-fpm:latest

docker build -t oniki/php-build .
docker tag oniki/php-build:latest registry.gitlab.com/mgs-software/oniki/oniki-api/php-build:latest
docker push registry.gitlab.com/mgs-software/oniki/oniki-api/php-build:latest
