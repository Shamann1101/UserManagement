# PHP developer test task

## Subject:
Create an API which allow users and groups management
Out of scope: authentication, and roles management; forms and views

## User Stories:
- I want to create a user who is included in a group
- I want to check if this user exists and is active
- I want to modify the list of users of a group

## Entities:
- User: email, last name, first name, state (active/ non active), creation date
- Group: name

## API methods:
- /users/ fetch(retrieve) list of users
- /users/ create a user
- /users/id/ fetch info of a user
- /users/id/ modify users info
- /groups/ fetch list of groups
- /groups/ create a group
- /groups/id/ modify group info

## Bonus:
- to perform a functional test
- add validation constraints/rules

## Framework:
any modern MVC PHP framework - Symfony, Yii, Laravel or similar

## Install:
```shell
git clone git@github.com:Shamann1101/UserManagement.git
cd UserManagement
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load
php bin/console sever:run
```
## Access:

For SUPER_ADMIN_ROLES:
- user: admin
- password: admin

You can also try other users with random roles::
- user: test[0-9]
- password: test
