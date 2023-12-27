DOCKER_CMD=docker-compose

GREEN=\033[0;32m
YELLOW=\033[0;33m
RED=\033[0;31m
RESET=\033[0m

help:
	@echo "${GREEN}Project: Mine Certification ${RESET}"
	@echo "${GREEN}Commands available: ${RESET}"
	@echo " install\t\t\t\tInstall the project"
	@echo " build\t\t\t\t\tBuild images for the project"
	@echo " up\t\t\t\t\tStarts containers"
	@echo " down\t\t\t\t\tRemove containers"
	@echo " migrate\t\t\t\tMigrate database"
	@echo " seed\t\t\t\t\tSeed database with fake data"

check:
	@echo "${YELLOW}You are going to install a fresh environment ${RESET}"
	@printf "${RED}Are you sure? [y/N] ${RESET}" && read answer && [ $${answer:-N} = y ]

init:
	@> .env
	@echo "${YELLOW}Creating .env file...${RESET}"
	@cat .env.example >> .env

	@printf "${RED}Enter the database username: ${RESET}"
	@read username; \
	sed -i '' "s/^DB_USERNAME=.*/DB_USERNAME=$$username/" .env

	@printf "${RED}Enter the database password: ${RESET}"
	@stty -echo; \
    	read password; \
    	stty echo; \
    	echo; \
    	sed -i '' "s/^DB_PASSWORD=.*/DB_PASSWORD=$$password/" .env

	@echo "${GREEN}.env file is finalized...${RESET}"

build:
	@echo "${YELLOW}Building Docker images...${RESET}"
	@${DOCKER_CMD} build
	@echo "${GREEN}Docker images are now built...${RESET}"

up:
	@echo "${YELLOW}Creating Docker containers...${RESET}"
	@${DOCKER_CMD} up -d
	@echo "${GREEN}Docker containers are now up...${RESET}"

down:
	@echo "${RED}Removing Docker containers...${RESET}"
	@${DOCKER_CMD} down
	@echo "${RED}Docker containers are now removed...${RESET}"

migrate:
	@${DOCKER_CMD} run artisan migrate:fresh

seed:
	@${DOCKER_CMD} run artisan migrate:refresh
	@${DOCKER_CMD} run artisan db:seed --force

asset:
	@${DOCKER_CMD} run npm run build

dev:
	@${DOCKER_CMD} run npm run dev

generate_key:
	@${DOCKER_CMD} run artisan key:generate

composer:
	@${DOCKER_CMD} run composer install

visit:
	@echo "Visit the app: http://localhost:8000/"

artisan:
	@${DOCKER_CMD} run artisan

install: check init build down up composer generate_key migrate seed asset visit
	@echo "${GREEN}Installation finished successfully... ${RESET}"

.PHONY: help
