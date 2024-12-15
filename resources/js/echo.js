import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { getUserData } from '@/helper/getUserData';

const userData = getUserData();
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    authEndpoint: import.meta.env.VITE_API_PATH + "/broadcasting/auth",
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            Authorization: "Bearer " + userData?.token
        },
    },
});
