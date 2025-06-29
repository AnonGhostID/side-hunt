.PHONY: help build up down restart logs shell mysql redis clean

# Default target
help:
	@echo "Available commands:"
	@echo "  build    - Build the Docker images"
	@echo "  up       - Start all services"
	@echo "  down     - Stop all services"
	@echo "  restart  - Restart all services"
	@echo "  logs     - Show logs from all services"
	@echo "  shell    - Access the application container shell"
	@echo "  mysql    - Access MySQL shell"
	@echo "  redis    - Access Redis CLI"
	@echo "  clean    - Remove all containers, volumes, and images"

# Build Docker images
build:
	docker-compose build

# Start all services
up:
	docker-compose up -d

# Stop all services
down:
	docker-compose down

# Restart all services
restart:
	docker-compose restart

# Show logs
logs:
	docker-compose logs -f

# Access application shell
shell:
	docker-compose exec app sh

# Access MySQL shell
mysql:
	docker-compose exec mysql mysql -u sidehunt_user -p sidehunt

# Access Redis CLI
redis:
	docker-compose exec redis redis-cli

# Clean everything
clean:
	docker-compose down -v --rmi all --remove-orphans