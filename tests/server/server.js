"use strict";

var app = require('express')();

app.get('/', function (req, res) {
    var html = ['page1', 'page2', 'page3', 'not-allowed'].map(function (pageName) {
        return '<a href="' + pageName + '">' + pageName + '</a><br />';
    }).join('');

    html = html + '<a href="https://spatie.be">Do not index this link</a>'

    console.log('Visit on /');

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

app.get('/robots.txt', function (req, res) {
    var html = 'User-agent: *\n' +
        'Disallow: /not-allowed';

    console.log('Visited robots.txt and saw\n' + html);

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

app.get('/:page', function (req, res) {
    var page = req.params.page;

    console.log('Visit on ' + page);

    var html = 'You are on ' + page + '. Here is <a href="/page4">another one</a>'

    if (page == 'page3') {
        html = html + 'This link only appears on page3: <a href="/page5">ooo page 5</a>'
    }

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

var server = app.listen(4020, function () {
    var host = 'localhost';
    var port = server.address().port;

    console.log('Testing server listening at http://%s:%s', host, port);
});
