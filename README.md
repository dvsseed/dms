
# DMS ![logo]
[![Build Status](https://travis-ci.org/Vuedo/vuedo.svg?branch=master)](https://travis-ci.org/Vuedo/vuedo) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)

## What is DMS?

DMS is an open source project built with Laravel v5.2 and Vue.js v1.0 and Vuedo v0.0.1. It is a live example of how everything works together.

## Website using Vuedo in production : [https://vuejs-news.com](https://vuejs-news.com)

Vue.js News is a place where News, Tutorials, Plugins, Showcases and more things regarding Vue are handpicked and shared with the community.

![Dashboard Overview](http://i.imgur.com/4AdbjsF.gif)

## Basic Features:

* Manage posts and media
* Categorize posts
* User Roles
* Content moderation
* Markdown Editor
* and more...

## Installation

Download this repo.

Rename `env.example` to `.env` and fill the options.

Run the following commands:

```
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
gulp
php artisan serve
```
If you are making changes to JavaScript or Styles make sure you run gulp watch.

## Technical Description

You can find a detailed description and list with the libraries used in development here()

## Issues

For technical questions and bugs feel free to open one issue.

## Contribution

Soon a roadmap for contribution will be added so everyone will be welcome to join.

## Stay In Touch

For latest releases and announcements, follow on Twitter: [@vuedo](https://twitter.com/vuedo) and Github: [@vuedo](https://github.com/Vuedo/vuedo)

## License

Vuedo is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
