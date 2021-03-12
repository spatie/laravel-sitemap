# Upgrading

## From 5.0 to 6.0

No API changes were made. If you're on PHP 8, you should be able to upgrade from v5 to v6 without having to make any changes.

## From 4.0 to 5.0

- `spatie/crawler` is updated to `^4.0`. This version made changes to the way custom `Profiles` and `Observers` are made. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles or observers - if you have any.

## From 3.0 to 4.0

- `spatie/crawler` is updated to `^3.0`. This version introduced the use of PSR-7 `UriInterface` instead of a custom `Url` class. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles - if you have any.
