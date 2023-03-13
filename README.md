<<<<<<< HEAD
# Makesense_final
=======
# Project 3 - MakeSense - Symfony

## Description

This project was realized during the Web Developer training of the Wild Code School,
For our 3rd and final project, we were asked to create a decision-making site for the company MakeSense.

### Team

- Jordan Lassalle
- Killian Portier
- Julien Bonzom
- Bixente Lasserre

## Technologies

### Front-end

- HTML/CSS
- Javascript
- Bootstrap

### Back-end

- PHP
- Symfony 6

## To Launch

### Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

### Install

1. Clone this project
2. Run `composer install`
3. Run `yarn install`
4. Run `yarn encore dev` to build assets
5. Copy .env to .env.local
6. Configure your DATABASE_URL
7. Configure your MAILER_DSN
8. Build database with php `bin/console doctrine:database:create`
9. Build migrations with php `bin/console doctrine:migrations:migrate`
10. Build fixtures with php `bin/console doctrine:fixtures:load`


### Manipulate

1. Run symfony server:start to launch your local php web server
2. Run yarn run dev --watch to launch server for assets
3. Sign in the Website with the admin
4. Make Fun!
>>>>>>> 235691b (push)
