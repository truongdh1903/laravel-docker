FROM node:14.19.0 as builder

WORKDIR /var/www/html

COPY ./frontend .

RUN yarn && yarn dev --port 3000