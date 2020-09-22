var express = require('express'),
    socket  = require('socket.io'),
    https = require('https');

var fs = require('fs');

var app = express();

var options = {
    key: fs.readFileSync('./csr.pem', 'utf8'),
    cert: fs.readFileSync('./server.crt', 'utf8')
};

var server = https.Server(options, app);

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