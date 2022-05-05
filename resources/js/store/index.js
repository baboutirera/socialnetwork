import Vuex from 'vuex';

import middleware from './modules/middleware.js';
import auth from './modules/auth.js';

export default new Vuex.Store({
    modules: {
        middleware,
        auth,
    }
})
