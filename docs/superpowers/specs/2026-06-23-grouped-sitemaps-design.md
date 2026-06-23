# Grouped sitemaps via a closure

## Goal

Let users split a crawled site into several sitemap files, choosing the destination file per URL with a closure, and optionally write a sitemap index that references those files.

Today `SitemapGenerator::writeToFile($path)` always produces a single sitemap (or count split chunks plus an auto index when `maxTagsPerSitemap()` is set). This feature adds grouping by an arbitrary key, derived from each URL.

## Context: is this worth building?

Splitting a small site into multiple sitemaps gives no ranking benefit. Google's limits are 50,000 URLs and 50MB per sitemap, and one sitemap versus many makes no crawling or ranking difference. The real value is diagnostic: Search Console reports indexing coverage per submitted sitemap, so "which section has indexing problems" becomes answerable at a glance. That is useful for a marketing team, but it is an operational nicety, not an SEO win.

This feature does not, on its own, fix duplicate or redirecting URLs appearing in a sitemap. That is solved by filtering (returning `null` from `hasCrawled`, or refusing URLs in `shouldCrawl`), which the package already supports.

## Public API

```php
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

SitemapGenerator::create('https://example.com')
    ->sitemapIndexPath(public_path('sitemap.xml'))           // optional
    ->writeToFile(fn (Url $url) => str_starts_with($url->path(), '/blog')
        ? public_path('sitemap-blog.xml')
        : public_path('sitemap-pages.xml'));
```

### `sitemapIndexPath(string $path): static`

New fluent setter. Records where the sitemap index should be written. If it is never called, no index is created.

### `writeToFile(string|Closure $path): static`

- **String argument:** unchanged. Today's behavior is preserved byte for byte, including the existing `maxTagsPerSitemap()` count split with its auto generated index.
- **Closure argument:** the closure receives a `Spatie\Sitemap\Tags\Url` (the same object `hasCrawled` works with; exposes `->url`, `->path()`, `->segments()`) and returns the absolute file path that URL belongs in.

## Behavior with a closure

1. The crawl runs as today, collecting `Url` tags.
2. After the crawl, each collected tag is passed to the closure to obtain its destination path.
3. Tags are bucketed by destination path. Each distinct path becomes one `Sitemap`, written to that path.
4. If a bucket exceeds `maxTagsPerSitemap()`, it sub splits using the existing `Sitemap` chunk logic (`sitemap-blog.xml` becomes `sitemap-blog_0.xml`, `sitemap-blog_1.xml`, ...). Decision: group first, then count split within each group.
5. If `sitemapIndexPath()` was set, a `SitemapIndex` listing every written child file is rendered and written there. Child file paths are converted to public URLs with the existing `toUrlPath()` helper. If `sitemapIndexPath()` was not set, no index is written.

## Edge cases

- **Overflow without an index:** if a bucket exceeds `maxTagsPerSitemap()` and no index path was set, the `_0/_1` chunk files are still written (URLs are never silently dropped), but nothing references them. This is documented as the caller's responsibility. We do not throw; throwing would be too paternalistic for users who deliberately opted out of an index.
- **Empty buckets:** a path returned for no URLs produces no file.
- **Closure returns `null` or an empty string:** the URL is skipped, written to no sitemap. This doubles as a filtering escape hatch (the same intent as returning `null` from `hasCrawled`). It is a deliberate caller choice, so unlike the overflow case there is nothing to reference and nothing is lost that the caller did not ask to drop.

## Out of scope (v1)

- `writeToDisk` grouping (S3 and other disks). Natural follow up, left out for now. The closure returning absolute filesystem paths does not map cleanly to disk relative paths, so disk support needs its own small design.

## Implementation notes

- The grouping bucketing and index assembly live in `SitemapGenerator::writeToFile()`. The per file rendering, count split chunking, and index rendering already exist on `Sitemap` (`maxTagsPerSitemap`, `shouldSplit`, `chunkTags`, `buildSplitSitemaps`) and `SitemapIndex`, and should be reused rather than reimplemented.
- Note for planning: `SitemapGenerator` currently keeps a `$this->sitemaps` collection and does its own count split inside `getSitemap()` / `writeToFile()`, while `Sitemap` also has its own count split. There is overlap. The closure path should collect a single flat set of tags and bucket them, rather than thread through the existing `$this->sitemaps` count split collection. Reconciling the two count split mechanisms is a candidate cleanup but should stay minimal and not become an unrelated refactor.

## Testing

- String path argument still writes a single sitemap (regression).
- Closure routing two URL groups writes two files with the expected tags in each.
- With `sitemapIndexPath()` set, an index file is written listing exactly the child files, with correct public URLs.
- Without `sitemapIndexPath()`, no index file is written.
- A bucket exceeding `maxTagsPerSitemap()` sub splits and (when an index is set) every chunk appears in the index.
