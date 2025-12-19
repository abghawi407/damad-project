import _ from 'lodash';
import axios from 'axios';
window._ = _;
window.axios = axios;

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || 'local',
    wsHost: process.env.MIX_PUSHER_HOST || window.location.hostname,
    wsPort: process.env.MIX_PUSHER_PORT || 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: false,
    enabledTransports: ['ws','wss']
});