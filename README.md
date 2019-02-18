# workout-api
The API to handle CRUD operations for workout plans

# Installation
For this project you have to install MAMP first

- Clone the repository to the /Applications/MAMP/htdocs
- Go to [phpMyAdmin](http://localhost:8888/phpMyAdmin) and create the new 
  database by running queries from `database/template.sql`

The API should be available by 
- GET `http://localhost:8888/workout-api/api/plan/read.php` 
- GET `http://localhost:8888/workout-api/api/plan/read_one.php?id=3`
- POST `http://localhost:8888/workout-api/api/plan/update.php` with body 
```
{
	"id": "3",
	"name": "New name",
	"description": "New description"
}
```
- etc. (CRUD operations for plans and users) and read operation for days

# Ideology
As a test assignment it was done very simple. 
So the entire business logic is in models, in real life it should be done separated to models, mappers, services, etc.
There is no proper routing, no autoload functionality, etc.
In real life it's better to implement such API in a framework (e.g. Phalcon)

There is no functionality to change days in workout plan - it should be the next step.

And then the next step - to implement unit tests with [PHPUnit](https://phpunit.de) and functional 
testing with [Codeception](https://codeception.com)

# Functionality

With this API you are able to:
- Create
- Load
- Edit
- Delete
workout plans and users.

A plan has a name and consists of several (workout) days.
A day can have multiple exercises that you should perform that day.
A plan can be assigned to one or more user(s).
An user is an entity with personal data (firstname, lastname, email)

Whenever a plan is modified, the user(s) connected should be notified of the change by mail.
