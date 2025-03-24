import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from "axios";

window.Pusher = Pusher;

axios.defaults.withXSRFToken = true;
axios.defaults.withCredentials = true;
axios.get('https://api.messaging-prototype.test/sanctum/csrf-cookie')
    .then( () => { console.log('Got cookie!'); })
    .catch((err) => { console.error('Error fetching CSRF token:', err); });

console.log('Setting up Echo!');

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => ({
        authorize: (socketId, callback) => {
            const payload = {
                socket_id: socketId, channel_name: channel.name
            }
            axios.post('/api/broadcasting/auth', {
                socket_id: socketId,
                channel_name: channel.name
            })
            .then((response) => {
                console.log(response.data);
                callback(false, response.data);
            })
            .catch((error) => {
                callback(true, error);
            });
        },
    }),
});
