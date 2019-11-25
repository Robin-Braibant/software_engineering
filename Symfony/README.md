composer install
composer update
composer -o dump-autoload

composer create-project symfony/website-skeleton voorbeeld1

composer require symfony/web-server-bundle --dev
php bin/console make:controller
php bin/console server:run

Routes bekijken
php bin/console debug:router

Statuscodes:
-200 = OK
-400 = Bad Request
-404 = Not found
-500 = internal server error

Coverage:
.\vendor\bin\simple-phpunit --coverage-html Coverage

Testing
.\vendor\bin\simple-phpunit

symfony.com/doc/4.1/testing/database.html

Routes na opstarten van de server (om de browser te testen)
127.0.0.1:8000/
    asset
    getByAssetName?name=testAsset
    room
    rooms
    getRoom?name=SeppesRoom
    getRoomsByHappinessScore?happinessScore=5
    updateHappinessScore?name=SeppesRoom&score=2
    ticket
    getTicketsByAssetName?assetName=testAsset
    createTicketByAssetName?assetName=testAsset&description=anotherTest
    updateNumberOfVotes?ticketId=2
    
    