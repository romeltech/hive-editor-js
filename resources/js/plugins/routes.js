import store from "../store";
import Home from "../components/admin/home";
import ForbiddenPage from "../components/admin/ForbiddenPage";
import NotFoundPage from "../components/admin/NotFoundPage";
import Users from "../components/admin/users/Users";
import EditUser from "../components/admin/users/EditUser";
import NewUser from "../components/admin/users/NewUser";

import Medias from "../components/admin/medias/Medias";
import EditMedia from "../components/admin/medias/EditMedia"; 
import NewMedia from "../components/admin/medias/NewMedia";
 
let adminOnly = ['superadmin', 'admin', 'moderator'];
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
        path: "/d/home",
        component: Home,
        name: "home",
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
     * Moderator Medias
     */
     {
        path: "/d/admin/medias",
        component: Medias,
        name: "medias",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/admin/medias/edit/:id",
        component: EditMedia,
        name: "EditMedia",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
     {
        path: "/d/admin/medias/page/:page",
        component: Medias,
        name: "page-medias",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },
    {
        path: "/d/admin/media/new",
        component: NewMedia,
        name: "NewMedia",
        beforeEnter: (to, from, next) => { 
            adminOnly.includes(auth.userObject.role) == true &&  auth.userObject.status == 'active'
                ? next()
                : next({ name: "ForbiddenPage" }); 
        }
    },

     /**
     * probation
     *
     */
    {
        path: "/d/probation", 
        beforeEnter() { window.open("https://apps.powerapps.com/play/e9518081-dc6e-4271-898e-f89256779243?tenantId=692f378a-47f8-404f-a997-88087a473145", '_blank'); }
    },
     
  

     /**
     * Performance
     *
     */
    {
        path: "/d/performance", 
        beforeEnter() { window.open("https://apps.powerapps.com/play/5fc03a77-0902-4004-b5eb-4886dc2b35ac?tenantId=692f378a-47f8-404f-a997-88087a473145", '_blank'); } 
    }, 

    {
        path: "/d/moderators", 
        beforeEnter() { window.location.href = '/d/admin/medias'; } 
    }, 

    {
        path: "/d/frontend", 
        beforeEnter() { window.location.href = '/d/home'; } 
    }, 
 
];
