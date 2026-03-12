---
title: Sorting URLs
weight: 7
---

You can sort the URLs in your sitemap alphabetically using the `sort()` method. This is useful for maintaining a consistent order in your sitemap files.

```php
use Spatie\Sitemap\Sitemap;

$sitemap = Sitemap::create()
    ->add('/zoo')
    ->add('/blog')
    ->add('/about')
    ->add('/contact')
    ->sort();
```

The `sort()` method will arrange all URLs in alphabetical order.

## Case Sensitivity

The sort operation is case-sensitive, with uppercase letters sorted before lowercase letters. For example:

```php
$sitemap = Sitemap::create()
    ->add('/Zebra')
    ->add('/apple')
    ->add('/BANANA')
    ->sort();

// Results in order: /BANANA, /Zebra, /apple
```

## Method Chaining

The `sort()` method returns the sitemap instance, allowing you to chain it with other methods:

```php
$sitemap = Sitemap::create()
    ->add('/page1')
    ->add('/page3')
    ->add('/page2')
    ->sort()
    ->writeToFile(public_path('sitemap.xml'));
```
