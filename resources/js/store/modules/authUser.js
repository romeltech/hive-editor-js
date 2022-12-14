let state = {
    userObject: {},
    userAccess: [],
};
let getters = {
    auth_user: state => state.userObject
};
const actions = {
    async fetchAuthUser({ commit }) {
        const response = await axios.get("/auth_user"); 
        commit("setAuthenticatedUser", response.data.user);
    }
};
const mutations = {
    setAuthUserAccess: (state, fetchAuthUser) => (state.userAccess = fetchAuthUser),
    setAuthenticatedUser: (state, fetchAuthUser) => (state.userObject = fetchAuthUser)
};

export default {
    // namespaced: true,
    state,
    getters,
    actions,
    mutations
};
