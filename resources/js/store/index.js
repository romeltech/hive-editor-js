import Vue from "vue";
import Vuex from "vuex";
import authUser from "./modules/authUser";

/**
 * Articles
 */
import singleArticle from "./modules/front/singleArticle";

Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        authUser,
        singleArticle,
    },
});

export default store;
