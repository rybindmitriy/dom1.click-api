beauty:
	vendor/bin/php-cs-fixer fix --allow-risky=yes

psalm:
	#docker exec -ti dom1click_php-fpm_1 /bin/sh -c "./vendor/bin/psalm --show-info=true"
	vendor/bin/psalm --show-info=true

test:
	vendor/bin/simple-phpunit

quality-check:
	make beauty
	make psalm
	make test
