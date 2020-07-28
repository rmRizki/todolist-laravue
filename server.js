const express = require('express')
const app = express()
const http = require('http').createServer(app);
const port = 3000
const io = require('socket.io')(http);

http.listen(port, () => console.log(`listening on port ${port}!`))

// app.get('/', (req, res) => res.send('Hello World!'))
// app.get('/', (req, res) => {
//     res.sendFile(__dirname + '/index.html')
// })

io.on('connection', (socket) => {
    console.log('A connection was made');
    socket.on('chat.message', (message) => {
        console.log(`New Message ${message}`);
        io.emit('chat.message', message);
    })

    socket.on('disconnect', () => {
        io.emit('chat.message', 'User has disconnected');
    })
})
