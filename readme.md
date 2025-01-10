# Gravity Forms Zoom Webinar Registration
* Contributors: michaelbourne
* Author: Michael Bourne
* Tags: zoom, gravity forms, webinar
* Requires at least: 5.0
* Tested up to: 6.6.2
* Stable tag: 1.3.0
* License: GPLv3 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

**Register attendees in your Zoom Webinar through a Gravity Form**

Now hosted on the WP repo by @apos37: https://wordpress.org/plugins/gravity-zwr/

This repo will no longer be mainted, please use the published plugin.

## Description

This plugin adds a "Zoom Registration" feed to your Gravity Forms. Although it was created specifically for the Webinars feature on Zoom, it will work with normal Meetings.

Updated in 2023 to work with OAuth.

## Requirements

 1. A WordPress.org based website
 2. The [Gravity Forms](https://www.gravityforms.com/) plugin
 3. A [Zoom](https://zoom.us/) account, Pro plan or higher
 4. Recommended: the [Webinar add-on](https://zoom.us/webinar) for your Zoom account
 5. A [Server-to-Server OAuth Application](https://marketplace.zoom.us/docs/guides/build/server-to-server-oauth-app/) created for your own Zoom account
 6. WordPress version 5+
 7. PHP version 8.0+

## License

Licensed with [GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/) 

Permissions of this strong copyleft license are conditioned on making available complete source code of licensed works and modifications, which include larger works using a licensed work, under the same license. Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.

**In other words, you can do anything you want with this plugin. However, you must leave original copyrights intact (that means credit to me for creating the plugin), and you acknowledge that this code is provided without warranty or liability.**

  

## Plugin Info

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

3. Optional: save and import the `gravity-forms-zoom-registration-sample-form.json` file from this repo into Gravity Forms as a starter form. All required and optional registration fields are included.

### Usage

After installation, go to Gravity Forms > Settings > Zoom Webinar. here you will enter your [Server OAuth App](https://marketplace.zoom.us/docs/guides/build/server-to-server-oauth-app/) Account ID, Client ID, and Client Secret. These apps are free to create, take only 5 minutes, and don't need to be published. Fill in all three  fields and hit Save.

Be sure to follow the directions on the Zoom API docs above carefully. You will need to edit roles in Zoom settings first, and then make the app. Your user role and app will both need the `meeting:write:admin` and `webinar:write:admin` scopes.

The Server-to-Server OAuth App in Zoom will need to be *active* bwefore this addon will work.

On the form you would like to use for registrations, go to Settings > Zoom Webinar. Add a new feed. Give it a name, choose the meeting type, enter your Meeting ID, and match the registration fields on the left to the form fields on the right. First name, last name, and email are rquired fields. The other fields are optional.

Be sure to enable registrations on your meeting if using that instead of webinars.

I *strongly* encourage you to enable logging in Gravity Frms settings when testing this add-on.

#### Using Constants

By default this plugin will ask for your Account ID, Client ID & Secret in the Gravity Forms settings, but users who want more control over how these are stored are welcome to specify these values as constants. The constants used by the plugin are `GRAVITYZWR_ACCOUNT_ID`, `GRAVITYZWR_CLIENT_ID`, and `GRAVITYZWR_CLIENT_SECRET`.

#### Payments

This add-on has support for "delayed payment support" through the Gravity Forms PayPal add-on. If desired, you can charge for your registrations via PayPal, and only process the Zoom Registration feed upon successful payment. [Read more here.](https://docs.gravityforms.com/setting-up-paypal-payments-standard/)

### Contributing

PRs are welcome from all developers. All I ask is you run `composer install` to download the dev dependencies. All PRs should be well commented and follow the coding standards file in this repo.

Translations are welcomed. Starter .po file is included in this repo for you to send a PR with your .mo file for WordPress. Text domain is `gravity-zwr`.

### Debuggers

Plugin comes with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

PHP_CodeSniffer needs to be installed with [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards)

## Notes

Always install and test new plugins on a staging site or development site prior to pushing to production. Support is not guaranteed for this plugin.

## Copyright

Gravity Forms Zoom Webinar Registration is a plugin for WordPress that enables you to add Commerce7 ecommerce integration into your site.

Copyright (c) 2020-2024 Michael Bourne & URSA6.

The Gravity Forms Zoom Webinar Registration Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>

You can contact me at michael@ursa6.com
