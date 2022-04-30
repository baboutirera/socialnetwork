import Vuex from 'vuex';

import middleware from './modules/middleware.js';

export default new Vuex.Store({
    modules: {
        middleware
    }
})
