# Google Maps Backend Task
I decided to use symfony 5 for a quicker setup of the task. I have one controller, located in `src/Controller/MapsController.php` which has 2 endpoints - one for the Google Maps service and one for the Open Street Service:

Google Maps Endpoint

`GET /maps/google/{address}` - where the address is the address which we're looking up

Open Street Maps

`GET /maps/openstreet/{address}` - where the address is the address which we're looking up

I inject the both services - GoogleMaps and OpenStreetMaps in the controller through the symfony's autowiring dependency injection mechanism.
The search method returns me MapsDTO - Data Transfer Object which holds the data of both responses.

My intention to use DTO was to provide equal data interface for both services.

Let's take a look into the services:

`App\Services\Maps\AbstractMap` - this is the abstract class, which shares common logic for all services. It could be eaisly inherited. All you have to do is to transform the response from the HttpClient to MapsDTO object and to construct an endpoint URL.

`App\Services\Maps\GoogleMaps` - the child class, which inherits the AbstractMap class. It holds the specific business logic for Google Maps 

`App\Services\Maps\OpenStreetMaps` - It is the same for the OpenStreetMaps

## setup
0) clone the repo: git clone https://github.com/man0l/google-maps-task-backend.git
1) install Symfony from this link: https://symfony.com/download
2) navigate to the directory and install the dependencies: `composer install`
3) and run the server with the command: `symfony serve`

## run tests

Execute the command and it will run all tests located in tests/

`php bin/phpunit`
