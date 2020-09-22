var express = require('express'),
    socket  = require('socket.io'),
    http = require('http');

var app = express();
var server = http.Server(app);


var io = socket(server, {
    path: '/socket.io'
});

app.get('/', function (req, res) {
    console.log('a');
    res.end('welcome');
});

app.get('/socket', function (req, res) {
    console.log('socket');
    res.end('welcome');
});

server.listen(8890, function(){
    console.log('Express server listening on port %d in %s mode', 8890, app.get('env'));
});

io.on('connection', function (socket) {

    console.log("New client connected");

    socket.on('disconnect', function() {
        console.log('Server has disconnected');
    });

});