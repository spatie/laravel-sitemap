# Upgrading

Because there are many breaking changes an upgrade is not that easy. There are many edge cases this guide does not cover. We accept PRs to improve this guide.

## From 4.0 to 5.0

- `spatie/crawler` is updated to `^4.0`. This version made changes to the way custom `Profiles` and `Observers` are made. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles or observers - if you have any.

## From 3.0 to 4.0

- `spatie/crawler` is updated to `^3.0`. This version introduced the use of PSR-7 `UriInterface` instead of a custom `Url` class. Please see the [UPGRADING](https://github.com/spatie/crawler/blob/master/UPGRADING.md) guide of `spatie/crawler` to know how to update any custom crawl profiles - if you have any.
