"use strict";

var app = require('express')();

app.get('/', function (req, res) {
    var html = ['page1', 'page2', 'page3'].map(function (statusCode) {
        return '<a href="' + statusCode + '">' + statusCode + '</a><br />';
    }).join('');

    html = html + '<a href="https://spatie.be">Do not index this link</a>'

    console.log('Visit on /');

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

app.get('/:page', function (req, res) {
    var page = req.params.page;

    console.log('Visit on ' + page);

    var html = 'You are on ' + page + '. Here is <a href="/page4">another one</a>'

    res.writeHead(200, { 'Content-Type': 'text/html' });
    res.end(html);
});

var server = app.listen(4020, function () {
    var host = 'localhost';
    var port = server.address().port;

    console.log('Testing server listening at http://%s:%s', host, port);
});
