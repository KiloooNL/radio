# radio
SAM Broadcaster is an Internet radio broadcasting application by Spacial. The name "SAM" is an acronym for Streaming Audio Manager, which describes the software's functionality.

When you install SAMBC, it will include a PHP folder that you can upload to your web server and access remotely for users to queue songs in the playlist, buy music, lookup a track's information, amongst many [other features](https://spacial.com/what-we-do-features/)

"Radio" is my custom re-design of the SAMBC PHP site, included within it is a massively modified style, a totally reworked codebase, better and more responsive search engine, as well as other cool things. After using this version you may never go back to the stock PHP site.

This is a project I have worked on over the years, has gone through many revisions and changes and will probably continue to do so, indefinitely.

So go ahead, what are you waiting for, download or fork this project today

### Installation.
radio requires the following to run:
- [PHP 5.0](http://php.net/downloads.php)+
- [SAM Broadcaster](https://spacial.com/download-sam-broadcaster/)
- [MySQL 5](https://www.mysql.com/downloads/)+
- [Apache 2.4](https://httpd.apache.org/) or similar web server

>Installation procedure:
 1. (TODO: Currently missing) is the install.sql file located in /install/, this must be imported to your MySQL server for radio to function.
2. Copy this entire git to your httpd directory, maybe in a folder called /radio/
3. Go to the /config/ folder and open the following files: config.php & dbconfig.xml.php

In config.php, you will need to edit the following variables:
```sh
define('STATION_NAME', 'Your Station Name');
define('STATION_EMAIL', 'Your Email');
define('STATION_ID', 142277); // Your Spacial.net Station ID
```

And in dbconfig.xml.php you will need to edit these settings:
```sh
<CONFIG application="SAM" version="3.4.2">
	<Database>
		<Driver>MYSQL</Driver>
		<Host>Your SQL IP here</Host>
		<Port>3306</Port>
		<Database>SAMDB</Database>
		<Username>MySQL username</Username>
		<Password>MySQL password</Password>
	</Database>
</CONFIG>
```
With these settings configured, you should be able to open [/radio/](http://localhost/radio/) at http://localhost/radio/ - or wherever you placed the /radio/ folder relative to your httpd folder.

### Development
Want to contribute? Great!

Radio is coded in PHP, HTML, Javascript and CSS. It uses the [Zend](https://framework.zend.com/) for more advanced features.
The way most of the rebuild has happened has generally been in-line with SAM BC's variables, so if you follow the guides over at the [wiki](http://support.spacialaudio.com/wiki/SAM_PHP_Web_Pages_-_Windows_Installation), you shouldn't have much issues jumping onto this project.

Submit a fork and I'll be glad to review!

### Credits & Thanks
- Main developer - [Ben Weidenhofer](http://www.github.com/KiloooNL)
- Thanks to [TheJosh](http://www.github.com/TheJosh) for input on the search engine and its functionality

### Screenshots
Here's a few screenshots of the theme:

![DJ KiloooNL'z Radio](http://i.imgur.com/tPrghUd.png)
![DJ KiloooNL'z Radio](http://i.imgur.com/mmHKGFk.png)
![DJ KiloooNL'z Radio](http://i.imgur.com/6T0ZCJZ.png)
