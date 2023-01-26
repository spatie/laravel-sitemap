# Changelog

All notable changes to `laravel-sitemap` will be documented in this file

## 6.2.5 - 2023-01-26

### What's Changed

- Revert "fix for maximum crawl limit (#470)" by @BoGnY in https://github.com/spatie/laravel-sitemap/pull/490

### New Contributors

- @BoGnY made their first contribution in https://github.com/spatie/laravel-sitemap/pull/490

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.2.4...6.2.5

## 6.2.4 - 2023-01-24

### What's Changed

- Refactor tests to Pest by @alexmanase in https://github.com/spatie/laravel-sitemap/pull/476
- Add Dependabot Automation by @patinthehat in https://github.com/spatie/laravel-sitemap/pull/480
- Add PHP 8.2 Support by @patinthehat in https://github.com/spatie/laravel-sitemap/pull/479
- Bump actions/checkout from 2 to 3 by @dependabot in https://github.com/spatie/laravel-sitemap/pull/483
- Fix github badges by @Tofandel in https://github.com/spatie/laravel-sitemap/pull/488
- Add laravel 10.x Compactibility by @njoguamos in https://github.com/spatie/laravel-sitemap/pull/489

### New Contributors

- @alexmanase made their first contribution in https://github.com/spatie/laravel-sitemap/pull/476
- @patinthehat made their first contribution in https://github.com/spatie/laravel-sitemap/pull/480
- @dependabot made their first contribution in https://github.com/spatie/laravel-sitemap/pull/483
- @Tofandel made their first contribution in https://github.com/spatie/laravel-sitemap/pull/488
- @njoguamos made their first contribution in https://github.com/spatie/laravel-sitemap/pull/489

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.2.3...6.2.4

## 6.2.3 - 2022-10-24

### What's Changed

- Image title and license shouldn't be URLs by @madman-81 in https://github.com/spatie/laravel-sitemap/pull/475

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.2.2...6.2.3

## 6.2.2 - 2022-09-22

### What's Changed

- Fix maximum crawl count by @Akilez in https://github.com/spatie/laravel-sitemap/pull/470

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.2.1...6.2.2

## 6.2.1 - 2022-08-23

### What's Changed

- Update .gitattributes by @angeljqv in https://github.com/spatie/laravel-sitemap/pull/452
- Update sitemap.php by @jbrooksuk in https://github.com/spatie/laravel-sitemap/pull/464

### New Contributors

- @angeljqv made their first contribution in https://github.com/spatie/laravel-sitemap/pull/452
- @jbrooksuk made their first contribution in https://github.com/spatie/laravel-sitemap/pull/464

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.2.0...6.2.1

## 6.2.0 - 2022-06-13

### What's Changed

- Add the possibility to add images to sitemaps by @madman-81 in https://github.com/spatie/laravel-sitemap/pull/451

### New Contributors

- @madman-81 made their first contribution in https://github.com/spatie/laravel-sitemap/pull/451

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.1.1...6.2.0

## 6.1.1 - 2022-06-06

### What's Changed

- remove gap in sitemapIndex output by @Flowdawan in https://github.com/spatie/laravel-sitemap/pull/449

### New Contributors

- @Flowdawan made their first contribution in https://github.com/spatie/laravel-sitemap/pull/449

**Full Changelog**: https://github.com/spatie/laravel-sitemap/compare/6.1.0...6.1.1

## 6.1.0 - 2022-01-14

- allow Laravel 9

## 6.0.5 - 2021-08-11

- fix crawler integration (#407)

## 6.0.4 - 2021-05-27

- compatible with the spatie/crawler v7 contracts

## 6.0.3 - 2021-05-26

- allow spatie/crawler v7

## 6.0.2 - 2021-04-09

- bump temporary-directory to version 2.0 (#379)

## 6.0.1 - 2021-03-26

- do not sort links by default

## 6.0.0 - 2021-03-12

- add `Sitemapable`
- drop support for PHP 7

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
