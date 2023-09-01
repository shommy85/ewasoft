# Ewasoft assigment
## Project setup

For running and the project setup, docker compose tool is used, so it needs to be
pre-installed.

After cloning repository from the Github, *cd* to the project folder and execute:

```docker compose up```

This command should start all the containers, create microservices databases, execute migrations and start needed workers in each container in order to process messages from the queues.

For helping with API interactions in the folder "postman" there are exported Postman collection and environment. 
Please use "Gateway" folder as other microservices are currently not publicly accessible via API.

## Project structure
* "postman" folder contains Postman collection and environment as previously mentioned.
* "docker" folder contains docker needed files for project provisioning.
* "symfony" is basic Symfony installation, that helped to create microservices. It is not otherwise used in the project.
* Other folders: "direct_coms", "gateway", "likes", "posts", "users" are corresponding microservices folders.

## Project tech stack
* Docker with docker compose tool
* Symfony 6.3
* PHP 8.2.9
* RabbitMq 3.12
* PostgeSQL 14.9
* Apache
* Ubuntu

## Project architecture and implementation details

### API gateway microservice
There is an **API gateway microservice** which is single point of entry and is mostly just forwarding calls to other microservices.
It is available on "http:\\localhost:8081\api".
Other microservices are made publicly un-accessible, but they can be easily configured to be for testing reasons.
At the moment gateway is using simple dynamic mapping technique based on URL to just proxy requests to other microservices.
In general, we should have some concrete mapping, so that all other microservices endpoints cannot be discovered with API gateway.
Also, there is one data aggregation that gateway does when fetching one post, it adds current user likes.


### User microservice
#### Authentication
**User microservice** is used for authentication purpose and also contains CRUD operations for the user.
JWT token is used for authentication and is obtained on endpoint:
```http://localhost:8081/api/users/login_check```

but of course user needs to be registered beforehand with endpoint:
```http://localhost:8081/api/users/register```

The logic behind the authentication is, that via gateway proxying to the user service, token is generated and returned to the client.
It is then clients responsibility to supply that token on other API requests.
Other microservices use that token too in order to authenticate user, but for all other services except for user, their user is just a DTO object with information obtained from the token.

#### Authorisation
JWT token contains Symfony user roles which are used across microservices for authorisation. For simplicity reasons there are no roles defined but the USER_ROLE.
In general there should be defined and assigned role for every action e.g. CREATE_POST_ROLE and with that role we would protect corresponding endpoint.
Now endpoint either requires just authentication or it is publicly accessible.

#### File upload
Profile image upload endpoint is using form multipart content type for better performance. 
Also, simple field is used for saving file name for limited time implementation reasons. 
Otherwise, file metadata would be saved in the table as separate entity, and we would have services for file url generation, resizing images etc. something like sonata image bundle implementation

### Post likes microservice
Creating and deleting likes is implemented in simple way by calling appropriate endpoint. Here the validation is used to check if post actually exists which directly calls post microservice.
These operations could be made asynchronous by API gateway queueing these requests, but in that way we would also need to think about if additional implementation is needed for notifying user about success of operation, like web sockets.

### Posts microservice
There is an authorisation logic for making sure that correct user can make CRUD operations.
When post is deleted, event is sent to the queue and "like" microservice is using it to delete post likes.

### Direct communication microservice
Just a simple implementation of creating a message. When it is saved in database, an event is queued so it can be picked up by another service for further actions if needed.

### RabbitMq
For consuming flexibility topic type brokers were used everywhere.
Admin panel for the RabbitMq is accessible on the URL:
```http://localhost:15672/#/queues/%2F/messages```
with credentials user "guest" and password "guest".

## User registration and login
If using supplied postman collection look in folder "Gateway";
1. Register user with endpoint "Register User":
```http://localhost:8081/api/users/register```
2. Login user with endpoint "Login User":
```http://localhost:8081/api/users/login_check```

Here the token is returned that needs to be used across all other requests.
If using postman and imported environment, this will be automatically pre-populated for every other request.
Token is transmitted via "Authorisation" header.

**Note: please make sure that username and password for both requests are matching.**

## Some further code and logic improvements
* Adding user roles and hierarchies for all the actions
* Moving json_decode logic from controllers to request listener so we can directly obtain data from request
* Creating CRUD controller abstraction so it could be reused.
* Method annotation and documentation.
* Code that is reused across microservices could be packed into bundles and made available in composer repository so it does not have to be copied.
* Slightly better folder organisation of project.