FROM phalconphp/php:alpine-php7

ARG BUILD_ID=0
ARG VERSION=0.0.1

ENV SERVICE_ENV=dev \
    SERVICE_VERSION=${VERSION} \
    APPLICATION_NAME=elevator \
    BUILD_ID=${BUILD_ID} \
    NUMPROCS=1

COPY . /app

RUN \
    # Install PHP dependencies
    cd $APPLICATION_PATH \

    && chmod +x $APPLICATION_PATH/run \

    # Setting up the logs paths
    && mkdir -p /var/log/$APPLICATION_NAME \
    && chown -R $APPLICATION_USER:$APPLICATION_GROUP /var/log/$APPLICATION_NAME \

    # Configs
    && mkdir -p /etc/$APPLICATION_NAME \
    && mv $APPLICATION_PATH/config/restart-workers /usr/local/bin/ \
    && chmod +x /usr/local/bin/restart-workers \
    && cp $APPLICATION_PATH/config/$APPLICATION_NAME.ini.dist /etc/$APPLICATION_NAME/$APPLICATION_NAME.ini \

    # Supervisord tasks
    && rsync -a $APPLICATION_PATH/config/supervisor.d/* /opt/docker/etc/supervisor.d/

# Set workdir
WORKDIR $APPLICATION_PATH

# Expose ports
EXPOSE 9000 9001

VOLUME ["/var/log/$APPLICATION_NAME"]
