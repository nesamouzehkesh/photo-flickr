## Photo-flickr

Photo-flickr is a project that uses Flickr API and Symfony3 to create a photo gallery management system for you. In the homepage, for instance, you can see the galleries listed on the left and click on your chosen gallery to see the related photos on the right.


## Installation:

- Set up MySQL and Apache.

- Clone the project on your local drive.

- Navigate to the project’s directory.

- Run  ./install.sh (if the file needs execution permission then give execution permission to it).

- install.sh will first try to run the Composer to install all the necessary vendors for the system; when it asks for database settings (name/username/password) enter valid data of your choice. It then tries to install all the schemas.

- Finally, install.sh will run a data fixture command to populate some dummy data into your database.

- When everything is installed click on “admin” button; the 

credentials are:
Username: admin@admin.com
Password: admin
