# Changelog

All notable changes to `laravel-sitemap` will be documented in this file

## 5.9.2 - 2021-03-04

- allow crawler v6 (#365)

## 5.9.1 - 2020-12-30

- add `filter()` method to tags before rendering (#347)

## 5.9.0 - 2020-12-27

- add support for PHP 8

## 5.8.0 - 2020-09-08

- add support for Laravel 8

## 5.7.0 - 2020-03-03

- add support for Laravel 7

## 5.6.0 - 2019-01-20

- add `writeToDisk` (#283)

## 5.5.0 - 2019-09-24

- implement `Responsable` contract

## 5.4.0 - 2019-09-04

- make compatible with Laravel 6

## 5.3.1 - 2019-09-02

- make sure the sitemap cannot contain duplicate URLs

## 5.3.0 - 2019-02-27

- drop support for Laravel 5.7 and below
- drop support for PHP 7.1 and below

## 5.2.11 - 2019-02-27

- add support for Laravel 5.8

## 5.2.10 - 2019-02-15

- fix some formatting issues

## 5.2.9 - 2019-02-10

- fix headers

## 5.2.8 - 2019-01-07

- adhere to sitemap standards

## 5.2.7 - 2018-11-03

- enforce priority value to be in between 0 and 1.

## 5.2.6 - 2018-11-03

- use absolute urls

## 5.2.5 - 2018-10-17

- Remove unused dependency

## 5.2.4 - 2018-09-17

- Make all generated links absolute

## 5.2.3 - 2018-08-28

- Add support for Laravel 5.7

## 5.2.2 - 2018-08-26

- Make methods on `SitemapGenerator` fluent

## 5.2.1 - 2018-07-23

- Improve indentation of rendered output

## 5.2.0 - 2018-05-08

- Support robots checks.

## 5.1.0 - 2018-04-30

- add support for a maximum amount of tags in one sitemap

## 5.0.1 - 2018-03-02

- Bump minimum required crawler version to `^4.0.3`

## 5.0.0 - 2018-03-02

- Update to Crawler 4.0

## 4.0.0 - 2018-02-08

- Update to Laravel 5.6
- Update to phpunit 7
- Update to Crawler 3.0

## 3.3.1 - 2018-01-11
- avoid having duplicates in the sitemap

## 3.3.0 - 2017-11-03
- add `setMaximumCrawlCount`

## 3.2.2 - 2017-10-19
- fix custom profiles

## 3.2.1 - 2017-10-07
- fix bug introduced in 3.2.0

## 3.2.0 - 2017-10-03
- add `crawl_profile` config key

## 3.1.0 - 2017-09-22

- add ability to execute JavaScript

## 3.0.0 - 2017-08-31

- support for Laravel 5.5
- dropped support for older Laravel versions

## 2.4.0 - 2017-08-22

- add support for alternates

## 2.3.1 - 2017-08-08

- move snapshots to dev dependencies

## 2.3.0 - 2017-08-05

- added config file with guzzle options

## 2.2.1 - 2017-05-05

- fix whitespace problems in sitemap indexes

## 2.2.0 - 2017-04-22

- add support for sitemap indexes

## 2.1.2 - 2017-01-27

- fix bugs in `getUrl` and `hasUrl`

## 2.1.1 - 2017-01-24

- fix for installations that are using short open tags

## 2.1.0 - 2017-01-24

- add support for Laravel 5.4

## 2.0.0 - 2016-12-05

- improve speed of generator by crawling with multiple concurrent connections

## 1.1.0 - 2016-12-01

- add compatibility for Laravel 5.2

## 1.0.1 - 2016-10-09

- remove unused config file

## 1.0.0 - 2016-08-29

- initial release
