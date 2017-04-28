const http = require('http')
const socket = require('socket.io')

const server = http.createServer()
const io = socket.listen(server)
const port = 3000

server.listen(port, function() {
    console.log('Server listening at', port)
})

io.on('connection', function(socket) {

    socket.on('create_guestbook', function() {
        io.emit('replace_guestbook')
        io.emit('replace_unread_counter')
    })

    socket.on('check_as_read', function() {
        io.emit('replace_guestbook')
        io.emit('replace_unread_counter')
        io.emit('replace_read_counter')
    })

    socket.on('update_guestbook', function() {
        io.emit('replace_guestbook')
    });

    socket.on('delete_guestbook', function() {
        io.emit('replace_guestbook')
        io.emit('replace_read_counter')
        io.emit('replace_unread_counter')
    });
})
