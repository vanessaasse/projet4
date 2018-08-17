projet4
========

"Projet 4" is a Symfony project created between May and August 2018.
It's my fourth project with Openclassrooms. 

The project’s target is to create the ticketing of Louvre Museum.


**REQUIREMENTS**

- PHP 5.5.9 or higher,
- and the usual Symfony application requirements.


**PRESENTATION OF THIS OPENCLASSROOMS'S PROJECT**

https://openclassrooms.com/fr/projects/developpez-un-back-end-pour-un-client

**INSTALLATION**

1. Go to your localhost file
2. Execute this command to install the project: `$ git clone https://github.com/vanessaasse/projet4.git`
3. Go to "Projet 4" file: `$ cd projet4`
4. Install Composer: `$ php composer-setup.php --install-dir=bin`
5. Go to parameters.yml in this project, connect you to PHPMYAdmin and give a name to database_name.
6. Create the database: `$ php bin/console doctrine:database:create`
7. Update database with project's entities : `$ php bin/console doctrine:database:update --dump-sql`
8. Run the project : `$ php bin/console server:run`

