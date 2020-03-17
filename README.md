# MusicGenieAPI
![ALT TEXT](code/public/logo.png?raw=true "Music Genie Logo")

This API allows you to translate a song lyrics into a specific language. It also gives you some information and links about your searched track. With the implemented Web GUI you get a nice presentation of the API's output.

## Getting Started

### Prerequisites

First check if you have installed docker and docker-compose:
```
docker -v
```
```
docker-compose -v
```

### Start Docker-Container

To start the containers you have to execute the docker-compose file:
```
sudo docker-compose up -d
```
### Use API
Get all supported languages and their name codes:
```
http://localhost/api/availableLanguages
```
To get all information about a song plus the translated lyrics and all links for last.fm just open the following link and replace the placeholders:
```
http://localhost/api/?Artist={artist}&SongTitle={songTitle}&fromLanguage={fromLanguage}&toLanguage={toLanguage}
```
## Changes

You have to restart your containers if you change one of your config-files.
```
sudo docker-compose restart
```

## Stop

If you want to stop the docker containers you have to type the following command to your command line:
```
sudo docker-compose stop
```
