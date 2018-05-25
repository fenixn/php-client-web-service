# php-client-web-service
A simple web service with clients, sections, and links. Client has one to many relationship with sections. Section has one to many relationship with link. Deleting a client will delete all sections that belongs to it. The sections being deleted will delete links that belongs to it.

## Setup
Rename the config.sample.php file to config.php and enter your MySQL database credentials.

## Request Methods
All request methods must be in JSON format.

### POST
Use the POST request method to add a new client, section or link. All inputs are required.
#### Client
{
  "type": "client",
  "name": "client_name"
}
#### Section
A section POST request requires a valid client id that the section belongs to.

{
  "type": "section",
  "id": "client_id",
  "name": "section_name"
}
#### Link
A link POST request requires a valid section id that the link belongs to.

{
  "type": "link",
  "id": "section_id",
  "name": "link_name"
}


### PUT
Use the PUT request method to update a client, section or link. All inputs are required.
#### Client
{
  "type": "client",
  "id": "client_id",
  "name": "new client name"
}
#### Section
{
  "type": "section",
  "id": "section_id",
  "client_id": "client_id",
  "name": "new section name"
}
#### Link
{
  "type": "link",
  "id": "link_id",
  "section_id": "section_id",
  "name": "new link name"
}

### DELETE
Use the DELETE request method to delete a client, section or link.
#### Client
{
  "type": "client",
  "id": "client_id"
}
#### Section
{
  "type": "section",
  "id": "section_id"
}
#### Link
{
  "type": "link",
  "id": "link_id"
}
