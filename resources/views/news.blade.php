<news:news>
    <news:publication>
        <news:name>{{ $news->name }}</news:name>
        <news:language>{{ $news->language }}</news:language>
    </news:publication>
    <news:title>{{ $news->title }}</news:title>
    <news:publication_date>{{ $news->publicationDate->toW3cString() }}</news:publication_date>
@foreach($news->options as $tag => $value)
    <news:{{$tag}}>{{$value}}</news:{{$tag}}>
@endforeach
</news:news>