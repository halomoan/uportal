/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import { Form, HasError, AlertError } from "vform";

import Role from "./Role";
Vue.prototype.$Role = new Role(window.user);

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

Vue.component("pagination", require("vue-pagination-2"));

import VueRouter from "vue-router";
Vue.use(VueRouter);

let routes = [
    {
        path: "/dashboard",
        component: require("./components/Dashboard.vue").default
    },
    {
        path: "/announces",
        component: require("./components/Announces.vue").default
    },
    {
        path: "/invoices",
        component: require("./components/Invoices.vue").default
    },
    {
        path: "/invoiced",
        component: require("./components/InvoiceDetail.vue").default
    },
    {
        path: "/printInvoice",
        component: require("./components/InvoiceDetail.vue").default
    },
    {
        path: "/gentoken",
        component: require("./components/GenToken.vue").default
    },
    { path: "/users", component: require("./components/Users.vue").default },
    {
        path: "/usergroup",
        component: require("./components/UserGroup.vue").default
    },
    {
        path: "/news",
        component: require("./components/News.vue").default
    },
    {
        path: "/profile",
        component: require("./components/Profile.vue").default
    },
    {
        path: "/userd",
        component: require("./components/UserDetail.vue").default
    },
    {
        path: "*",
        component: require("./components/NotFound.vue").default
    }
];

const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});

import VueProgressBar from "vue-progressbar";
Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "2px"
});

import moment from "moment";
import { currency } from "./currency";
Vue.filter("currency", currency);

Vue.filter("upText", function(text) {
    return text.toUpperCase();
});

Vue.filter("humanDate", function(date) {
    return moment(date).format("MMMM Do YYYY");
});
Vue.filter("formatNumber", function(value) {
    return numeral(value).format("0,0");
});

import Swal from "sweetalert2";
import Axios from "axios";
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});

window.Toast = Toast;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component(
//     "passport-clients",
//     require("./components/passport/Clients.vue").default
// );

// Vue.component(
//     "passport-authorized-clients",
//     require("./components/passport/AuthorizedClients.vue").default
// );

// Vue.component(
//     "passport-personal-access-tokens",
//     require("./components/passport/PersonalAccessTokens.vue").default
// );

Vue.component("not-found", require("./components/NotFound.vue").default);

window.Fire = new Vue();
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
    data: {
        searchText: "",
        hasNew: {
            Invoice: false
        }
    },
    methods: {
        searchhit() {
            Fire.$emit("GLOBAL_SEARCH");
        },

        newFlag(group, bValue) {
            switch (group) {
                case "INVOICES":
                    this.hasNew.Invoice = bValue;
                    break;
                default:
            }
        },
        logout() {
            Swal.fire({
                title: "Logout",
                text: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Logout"
            }).then(result => {
                if (result.value === true) {
                    axios
                        .post("/logout")
                        .then(response => {
                            localStorage.removeItem("auth_token");
                            localStorage.removeItem("expiration");
                            delete axios.defaults.headers.common[
                                "Authorization"
                            ];
                            this.$router.go("/login");
                        })
                        .catch(error => {
                            localStorage.removeItem("auth_token");
                            localStorage.removeItem("expiration");
                            delete axios.defaults.headers.common[
                                "Authorization"
                            ];
                            this.$router.go("/login");
                        });
                }
            });
        }
        // searchhit: _.debounce(() => {
        //     Fire.$emit("GLOBAL_SEARCH");
        // }, 1000)
    },

    mounted() {
        axios
            .get("api/flag")
            .then(({ data }) => {
                let flags = data.flags;
                flags.map(flag => {
                    this.newFlag(flag.name, flag.value);
                });
            })
            .catch(() => {});
    }
});
