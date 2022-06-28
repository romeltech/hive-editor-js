import Dashboard from "../components/admin/Dashboard";
import ForbiddenPage from "../components/admin/ForbiddenPage";
import NotFoundPage from "../components/admin/NotFoundPage";
import Users from "../components/admin/users/Users";
import EditUser from "../components/admin/users/EditUser";
import NewUser from "../components/admin/users/NewUser";

import Cars from "../components/admin/cars/Cars";
import EditCar from "../components/admin/cars/EditCar";
import NewCar from "../components/admin/cars/NewCar";

import Drivers from "../components/admin/drivers/Drivers";
import EditDriver from "../components/admin/drivers/EditDriver";
import NewDriver from "../components/admin/drivers/NewDriver";

import Notifications from "../components/admin/notifications/Notifications";
import EditNotification from "../components/admin/notifications/EditNotification";
import NewNotification from "../components/admin/notifications/NewNotification";
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
        name: "dashboard"
    },

    /**
     * Users
     *
     */
     {
        path: "/d/users",
        component: Users,
        name: "Users"
    },
    {
        path: "/d/user/new",
        component: NewUser,
        name: "NewUser"
    },
    {
        path: "/d/user/edit/:id",
        component: EditUser,
        name: "EditUser"
    },

     /**
     * Cars
     *
     */
      {
        path: "/d/cars",
        component: Cars,
        name: "Cars"
    },
    {
        path: "/d/car/new",
        component: NewCar,
        name: "NewCar"
    },
    {
        path: "/d/car/edit/:id",
        component: EditCar,
        name: "EditCar"
    },

     /**
     * Drivers
     *
     */
      {
        path: "/d/drivers",
        component: Drivers,
        name: "Drivers"
    },
    {
        path: "/d/driver/new",
        component: NewDriver,
        name: "NewDriver"
    },
    {
        path: "/d/driver/edit/:id",
        component: EditDriver,
        name: "EditDriver"
    },

     /**
     * Notifications
     *
     */
      {
        path: "/d/notifications",
        component: Notifications,
        name: "Notifications"
    },
    {
        path: "/d/notification/new",
        component: NewNotification,
        name: "Notification"
    },
    {
        path: "/d/notification/edit/:id",
        component: EditNotification,
        name: "EditNotification"
    },
];
