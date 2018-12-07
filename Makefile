SHELL         := $(shell which bash)
ROOT_DIR      := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
ARGS           = $(filter-out $@,$(MAKECMDGOALS))

APP_NAME       = elevator

COMPOSER_FILE  = $(ROOT_DIR)/composer.json

IMAGE_TAG      = alpine-php7
IMAGE_REPO    ?= hub.docker.com
IMAGE_BASE     = phalconphp/php

DOCKER_NETWORK = elevator_tier
DOCKER_VOLUMES = redis

VERSION ?= $(shell cat $(ROOT_DIR)/VERSION | head -n 1)
ifeq ($(VERSION),)
VERSION := 0.0.1
endif

ifeq ($(BUILD_ID),)
BUILD_ID := 0
endif

ifeq ($(DD_API_KEY),)
DD_API_KEY :=
endif

.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
.NOTPARALLEL: ;          # wait for this target to finish
Makefile: ;              # skip prerequisite discovery

.DEFAULT_GOAL = help     # Run make help by default

.PHONY: .title
.title:
	$(info Elevator Implementation v$(VERSION))

.PHONY: help
help: .title
	echo ''
	echo 'Usage:'
	echo ''
	echo '  make [target] [params]'
	echo ''
	echo ''
	echo 'Available targets:'
	echo ''

	echo '  ps:           Show service status'
	echo '  up:           Starts and attaches to containers for a service'
	echo '  install:      Up service and install composer'
	echo '  help:         Show this help and exit'
	echo '  infra:        Prepare infrastructure'
	echo '  check:        Check required files'
	echo '  update:       Update base image'
	echo '  docker-build:        Build or rebuild services'
	echo '  first-run:    Prepare infrastructure and run application (has to be launched first)'
	echo '  delete:             Stop and remove infrastructure. Lost all files'
	echo '  elevator_supervisor Run supervisor'
	echo '  elevator_call from={from} to={to} Run supervisor'
	echo '  elevator_status'

	echo ''
	echo ''
	echo 'Available params:'
	echo ''
	echo '  BUILD_ID      Container build id'
	echo '  VERSION       Service version'
	echo ''

.PHONY: check
check:
ifeq ($(wildcard $(COMPOSER_FILE)),)
	$(error Failed to locate the $(COMPOSER_FILE) file.)
endif

.PHONY: update
update:
	$(info Updating base image...)
	docker pull $(IMAGE_BASE)

.PHONY: up
up:
	docker-compose up -d

.PHONY: install
install: up
	docker-compose exec app composer install

.PHONY: docker-build
docker-build:
	$(info Building image...)
	docker build \
		--build-arg VERSION=$(VERSION) \
		--build-arg BUILD_ID=$(BUILD_ID) \
		-t $(IMAGE_BASE):$(IMAGE_TAG) \
		--no-cache \
		--rm \
		.

.PHONY: $(DOCKER_VOLUMES)
$(DOCKER_VOLUMES): %:
	docker volume create --name=$@

.PHONY: infra
infra:
	make $(DOCKER_VOLUMES)
	docker network create --driver bridge $(DOCKER_NETWORK)  2> /dev/null | true

.PHONY: delete
delete:
	docker-compose stop
	docker-compose kill
	docker-compose rm -fv
	docker network rm $(DOCKER_NETWORK)
	docker volume rm $(DOCKER_VOLUMES)

.PHONY: elevator_call
elevator_call:
	docker-compose exec app /usr/bin/php run elevator:call $(from) $(to)

.PHONY: elevator_status
elevator_status:
	docker-compose exec app /usr/bin/php run elevator:status

.PHONY: elevator_supervisor
elevator_supervisor:
	docker-compose exec app /usr/bin/php run elevator:supervisor

.PHONY: reset
reset: prune up

# Command format: make first-run GITHUB_TOKEN=YOUR_SECRET_TOKEN
.PHONY: first-run
first-run: make infra
	make docker-build
	make install

.PHONY: ps
ps:
	docker ps --format 'table {{.ID}}\t{{.Names}}\t{{.Status}}'

%:
	  @:
