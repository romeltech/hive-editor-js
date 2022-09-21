import store from "../store";
import Home from "../components/admin/Home";
import ForbiddenPage from "../components/admin/ForbiddenPage";
import NotFoundPage from "../components/admin/NotFoundPage";
import Users from "../components/admin/users/Users";
import EditUser from "../components/admin/users/EditUser";
import NewUser from "../components/admin/users/NewUser";

import Medias from "../components/admin/medias/Medias";
import EditMedia from "../components/admin/medias/EditMedia";
import NewMedia from "../components/admin/medias/NewMedia";

import Events from "../components/admin/events/Events";
import EditEvent from "../components/admin/events/EditEvent";
import NewEvent from "../components/admin/events/NewEvent";

import Polls from "../components/admin/polls/Polls";
import EditPoll from "../components/admin/polls/EditPoll";
import NewPoll from "../components/admin/polls/NewPoll";

import Trainings from "../components/admin/trainings/Trainings";
import EditTraining from "../components/admin/trainings/EditTraining";
import NewTraining from "../components/admin/trainings/NewTraining";

/**
 * Front
 */
import SingleArticle from "../components/front/articles/SingleArticle";

let adminOnly = ["superadmin", "admin", "moderator"];
let employee = ["normal"];
let auth = store.state.authUser;

function returnAccess(slug) {
    let hasAccess = false;
    auth.userObject.access.map((o, i) => {
        if (slug == o.slug) {
            hasAccess = true;
        }
    });
    return hasAccess;
}

function validateAccess(slug) {
    let hasAccess = false;
    if (
        auth.userObject.status == "active" &&
        auth.userObject.role == "superadmin"
    ) {
        hasAccess = true;
    } else if (
        auth.userObject.status == "active" &&
        adminOnly.includes(auth.userObject.role) == true &&
        returnAccess(slug)
    ) {
        hasAccess = true;
    }
    return hasAccess;
}
export const routes = [
    /**
     * Admin Pages
     */
    {
        path: "/forbidden",
        component: ForbiddenPage,
        name: "ForbiddenPage",
    },
    {
        path: "/notfound",
        component: NotFoundPage,
        name: "NotFoundPage",
    },

    {
        path: "/d/home",
        component: Home,
        name: "home",
        beforeEnter: (to, from, next) => {
            auth.userObject.status == "active"
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },

    /**
     * Users
     *
     */
    {
        path: "/d/admin/users",
        component: Users,
        name: "Users",
        beforeEnter: (to, from, next) => {
            validateAccess("users") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/users/page/:page",
        component: Users,
        name: "page-users",
        beforeEnter: (to, from, next) => {
            validateAccess("users") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/users/new",
        component: NewUser,
        name: "NewUser",
        beforeEnter: (to, from, next) => {
            validateAccess("users") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/users/edit/:id",
        component: EditUser,
        name: "EditUser",
        beforeEnter: (to, from, next) => {
            validateAccess("users") ? next() : next({ name: "ForbiddenPage" });
        },
    },

    /**
     * Moderator Medias
     */
    {
        path: "/d/admin/medias",
        component: Medias,
        name: "medias",
        beforeEnter: (to, from, next) => {
            validateAccess("medias") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/medias/edit/:id",
        component: EditMedia,
        name: "EditMedia",
        beforeEnter: (to, from, next) => {
            validateAccess("medias") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/medias/page/:page",
        component: Medias,
        name: "page-medias",
        beforeEnter: (to, from, next) => {
            validateAccess("medias") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/medias/new",
        component: NewMedia,
        name: "NewMedia",
        beforeEnter: (to, from, next) => {
            validateAccess("medias") ? next() : next({ name: "ForbiddenPage" });
        },
    },

    /**
     * Moderator Events
     */
    {
        path: "/d/admin/events",
        component: Events,
        name: "events",
        beforeEnter: (to, from, next) => {
            validateAccess("events") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/events/edit/:id",
        component: EditEvent,
        name: "EditEvent",
        beforeEnter: (to, from, next) => {
            validateAccess("events") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/events/page/:page",
        component: Events,
        name: "page-events",
        beforeEnter: (to, from, next) => {
            validateAccess("events") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/events/new",
        component: NewEvent,
        name: "NewEvent",
        beforeEnter: (to, from, next) => {
            validateAccess("events") ? next() : next({ name: "ForbiddenPage" });
        },
    },

    // Polls

    {
        path: "/d/admin/polls",
        component: Polls,
        name: "polls",
        beforeEnter: (to, from, next) => {
            validateAccess("polls") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/polls/edit/:id",
        component: EditPoll,
        name: "EditPoll",
        beforeEnter: (to, from, next) => {
            validateAccess("polls") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/polls/page/:page",
        component: Polls,
        name: "page-polls",
        beforeEnter: (to, from, next) => {
            validateAccess("polls") ? next() : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/polls/new",
        component: NewPoll,
        name: "NewPoll",
        beforeEnter: (to, from, next) => {
            validateAccess("polls") ? next() : next({ name: "ForbiddenPage" });
        },
    },

    // Trainings

    {
        path: "/d/admin/trainings",
        component: Trainings,
        name: "trainings",
        beforeEnter: (to, from, next) => {
            validateAccess("trainings")
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/trainings/edit/:id",
        component: EditTraining,
        name: "EditTraining",
        beforeEnter: (to, from, next) => {
            validateAccess("trainings")
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/trainings/page/:page",
        component: Trainings,
        name: "page-trainings",
        beforeEnter: (to, from, next) => {
            validateAccess("trainings")
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },
    {
        path: "/d/admin/training/new",
        component: NewTraining,
        name: "NewTraining",
        beforeEnter: (to, from, next) => {
            validateAccess("trainings")
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },

    /**
     * probation
     *
     */
    {
        path: "/d/probation",
        beforeEnter() {
            window.open(
                "https://apps.powerapps.com/play/e9518081-dc6e-4271-898e-f89256779243?tenantId=692f378a-47f8-404f-a997-88087a473145",
                "_blank"
            );
        },
    },

    /**
     * Performance
     *
     */
    {
        path: "/d/performance",
        beforeEnter() {
            window.open(
                "https://apps.powerapps.com/play/5fc03a77-0902-4004-b5eb-4886dc2b35ac?tenantId=692f378a-47f8-404f-a997-88087a473145",
                "_blank"
            );
        },
    },

    {
        path: "/d/moderators",
        beforeEnter() {
            let slug = "medias";
            if (validateAccess("medias")) {
                slug = "medias";
            } else if (validateAccess("events")) {
                slug = "events";
            } else if (validateAccess("polls")) {
                slug = "polls";
            } else if (validateAccess("users")) {
                slug = "users";
            } else if (validateAccess("trainings")) {
                slug = "trainings";
            } else if (validateAccess("careers")) {
                slug = "careers";
            } else if (validateAccess("package_ads")) {
                slug = "package_ads";
            } else if (validateAccess("cashless")) {
                slug = "cashless";
            } else if (validateAccess("marketplace")) {
                slug = "marketplace";
            } else if (validateAccess("suggestions")) {
                slug = "suggestions";
            }
            window.location.href = "/d/admin/" + slug;
        },
    },

    {
        path: "/d/frontend",
        beforeEnter() {
            window.location.href = "/d/home";
        },
    },

    /**
     * Employee's Routes
     */
    {
        path: "/d/articles/:id",
        component: SingleArticle,
        name: "SingleArticle",
        beforeEnter: (to, from, next) => {
            employee.includes(auth.userObject.role) == true
                ? next()
                : next({ name: "ForbiddenPage" });
        },
    },
];
