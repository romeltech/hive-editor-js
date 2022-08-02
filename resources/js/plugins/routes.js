import store from "../store";
import Dashboard from "../components/admin/Dashboard";
import ForbiddenPage from "../components/admin/ForbiddenPage";
import NotFoundPage from "../components/admin/NotFoundPage";
import Users from "../components/admin/users/Users";
import EditUser from "../components/admin/users/EditUser";
import NewUser from "../components/admin/users/NewUser";

import Cars from "../components/admin/cars/Cars";
import EditCar from "../components/admin/cars/EditCar";
import Incident from "../components/admin/cars/IncidentForm";
import NewCar from "../components/admin/cars/NewCar";

import Drivers from "../components/admin/drivers/Drivers";
import EditDriver from "../components/admin/drivers/EditDriver";
import NewDriver from "../components/admin/drivers/NewDriver";

import Notifications from "../components/admin/notifications/Notifications";
import EditNotification from "../components/admin/notifications/EditNotification";
import NewNotification from "../components/admin/notifications/NewNotification";
let adminOnly = ['superadmin', 'admin'];
let auth = store.state.authUser;
export const routes = [
    /**
     * Admin Pages
     */
    {
        path: "/forbidden",
        component: ForbiddenPage,
        name: "ForbiddenPage"
    },
    {
        path: "/notfound",
        component: NotFoundPage,
        name: "NotFoundPage"
    },
 
    {
        path: "/d/dashboard",
        component: Dashboard,
        name: "dashboard",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },

    /**
     * Users
     *
     */
     {
        path: "/d/users",
        component: Users,
        name: "Users",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/users/page/:page",
        component: Users,
        name: "page-users",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/user/new",
        component: NewUser,
        name: "NewUser",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/user/edit/:id",
        component: EditUser,
        name: "EditUser",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },

     /**
     * Cars
     *
     */
    {
        path: "/d/cars",
        component: Cars,
        name: "Cars",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/cars/page/:page",
        component: Cars,
        name: "page-cars",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/car/new",
        component: NewCar,
        name: "NewCar",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/car/edit/:id",
        component: EditCar,
        name: "EditCar",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/car/new-incident/:id",
        component: Incident,
        name: "Incident",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },

     /**
     * Drivers
     *
     */
    {
        path: "/d/drivers",
        component: Drivers,
        name: "Drivers",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/drivers/page/:page",
        component: Drivers,
        name: "page-drivers",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/driver/new",
        component: NewDriver,
        name: "NewDriver",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/driver/edit/:id",
        component: EditDriver,
        name: "EditDriver",
        beforeEnter: (to, from, next) => { 
            auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },

     /**
     * Notifications
     *
     */
      {
        path: "/d/notifications",
        component: Notifications,
        name: "Notifications",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/notifications/page/:page",
        component: Notifications,
        name: "page-notifications",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/notification/new",
        component: NewNotification,
        name: "Notification",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/notification/edit/:id",
        component: EditNotification,
        name: "EditNotification",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
];
