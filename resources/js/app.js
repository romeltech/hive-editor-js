/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

// window.Vue = require('vue').default;
import Vue from "vue";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import VueRouter from "vue-router";
import store from "./store";
import { routes } from "./plugins/routes";
import vuetify from "./plugins/vuetify";

Vue.use(VueRouter);
Vue.use(CKEditor);
const router = new VueRouter({
    routes,
    mode: "history",
});

const helpers_plugin = {
    install(Vue, options) {
        // Prints name initals
        Vue.prototype.printInitials = (text) => {
            return text
                .split(" ")
                .slice(0, 2)
                .join(" ")
                .split(" ")
                .map((n) => n[0])
                .join("");
        };

        // Date format
        Vue.prototype.formatDateHelper = (date) => {
            return new Date(date).toLocaleString("en-GB", {
                day: "2-digit",
                year: "numeric",
                month: "2-digit",
            });
        };

        Vue.prototype.dateTimeFormatter = (date) => {
            return new Date(date).toLocaleString("en-US", {
                minute: "numeric",
                hour: "numeric",
                day: "2-digit",
                year: "numeric",
                month: "short",
            });
        };

        Vue.prototype.timestampConvert = (date) => {
            return new Date(date).getTime();
        };

        Vue.prototype.isExpired = (date) => {
            let ndate = new Date(date).toLocaleString();
            ndate = new Date(ndate).getTime();
            var vd = new Date().getTime();

            var d = new Date(date);
            d.setDate(d.getDate() - 30);
            let vdate = d.toLocaleString();
            vdate = new Date(vdate).getTime();

            if (ndate > vd && vdate < ndate && vd > vdate) {
                return "orange accent-3";
            } else if (ndate <= vd) {
                return "red accent-4 white--text";
            }

            return null;
        };
        // Base URL
        Vue.prototype.$baseUrl = window.location.origin;
    },
};
Vue.use(helpers_plugin);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Global Components
 */
Vue.component(
    "navigation-main",
    require("./components/ui/navigation/NavigationMain.vue").default
);
Vue.component(
    "navigation-admin",
    require("./components/ui/admin/Navigation.vue").default
);
Vue.component("snack-bar", require("./components/ui/SnackBar.vue").default);
Vue.component(
    "dialog-loader",
    require("./components/ui/DialogLoader.vue").default
);
Vue.component(
    "confirmation-dialog",
    require("./components/ui/ConfirmationDialog.vue").default
);

Vue.component(
    "avatar",
    require("./components/ui/Avatar.vue").default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
store.dispatch("fetchAuthUser").then(() => {
    const app = new Vue({
        vuetify,
        store,
        router,
        data() {
            return {
                isLoading: true,
                // Login
                loginValid: true,
                loginEmail: "",
                loginEmailrules: [(value) => !!value || "Required"],
                loginPassword: "",
                loginPasswordrules: [(value) => !!value || "Required"],
            };
        },
        watch: {
            isLoading: function (newVal, oldVal) {
                this.isLoading = newVal;
            },
        },
        methods: {
            loginValidate() {
                if (this.$refs.form.validate()) {
                    this.snackbar = true;
                }
            },
            logout: function (event) {
                event.preventDefault();
                document.getElementById("logout-form").submit();
            },
        },
        created() {
            this.isLoading = false;
        },
    }).$mount("#app");
});
