# Gravity Forms Zoom Webinar Registration

**Register attendees in your Zoom Webinar through a Gravity Form**

This plugin adds a "Zoom Registration" feed to your Gravity Forms. Although it was created specifically for the Webinars feature on Zoom, it will work with normal Meetings.

### Requirements

 1. A WordPress.org based website
 2. The [Gravity Forms](https://www.gravityforms.com/) plugin
 3. A [Zoom](https://zoom.us/) account, Pro plan or higher
 4. Recommended: the [Webinar add-on](https://zoom.us/webinar) for your Zoom account
 5. A [JWT Application](https://marketplace.zoom.us/develop/create) created for your own Zoom account
 6. WordPress version 5+
 7. PHP version 7.1+

### License

Licensed with [GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/) 

Permissions of this strong copyleft license are conditioned on making available complete source code of licensed works and modifications, which include larger works using a licensed work, under the same license. Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.

**In other words, you can do anything you want with this plugin. However, you must leave original copyrights intact (that means credit to me for creating the plugin), and you acknowledge that this code is provided without warranty or liability.**

  

## Plugin

```shell

plugins/gravity-forms-zoom-webinar-registration/

├── gravity-forms-zoom-webinar-registration.php # → Primary plugin file

├── gravity-forms-zoom-registration-sample-form.json # → Sample Gravity Form with all registration fields. Download JSOn file and import into Gravity Forms.

├── includes/ # → Plugin core PHP classes

│ ├── class-gravityzwr.php # → Main Plugin Class

│ ├── class-gravityzwr-zoomapi.php # → Remote request abstraction class for Zoom

│ ├── class-gravityzwr-wordpressremote.php # → Remote request class for WordPress

├── languages/ # → Plugin language file

```

### Event logging

Built in logging via Gravity Forms logger for debugging purposes. Enable debug mode in Gravity Forms settings to activate.

### Installation

1. Download the ZIP for this repo

2. Upload to your WordPress website

### Contributing

PRs are welcome from all developers. All I ask is you run `composer install` to download the dev dependencies. All PRs should be well commented and follow the coding standards file in this repo.

Translations are welcomed.

### Debuggers

Plugin comes with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

PHP_CodeSniffer needs to be installed under `/usr/local/bin/phpcs` with [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards)

## Notes

Always install and test new plugins on a staging site or development site prior to pushing to production. Support is not guaranteed for this plugin.