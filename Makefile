build:
	docker-compose build
	cd app && composer update --ignore-platform-reqs

run:
	docker-compose up -d
	docker-compose logs -f

stop:
	docker-compose kill
	docker-compose rm -f

database-clean:
	rm -rf mysql/data/*

docker-clean:
	docker-compose down

clean: docker-clean database-clean

deploy:
	rsync -aLOPv . konstantin@jakobi.club:/home/konstantin/docker/mmi/
