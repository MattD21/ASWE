# Equipment Management System
This is a project I worked on during the duration of my spring 2023 semester at UTSA. The course was Advanced Software Engineering, and the result is an application hosted on an AWS server, utilizing InnoDB and MySQL to store and query over 5 million rows of records, and PHP and HTML for backend and frontend coding.
# Import
The import directory holds the files to perform an import onto the server, into the equipment database, import-args.php is where the parsing and reading of data is done. In order to save space on the database, there are seperate tables containing manufacturer names and types of devices. The database holds an item, each item has an auto-id, type, manufacturer, and a unique SN. 
# Web-Endpoint
The web-endpoint directory houses the search, insert, and modify main features of the web application. While the site is no longer being hosted, these functionalities allowed a user to search the database under a variety of different criteria, insert new types, manufacturers, and entire rows of equipment onto the database, and to modify pre-exising rows of data pertaining to the types, manufacturers and equipment as a whole. 
# API
The api aspect of this project was migrating the web-endpoint we already created, to work with an api. This was done to avoid running database queries on the actual web endpoint as that is a bad practice, this was done to correct that. The API endpoint only contains the search and insert features, although the modify could be easily implemented.
