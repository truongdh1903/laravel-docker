import { createWebHistory, createRouter } from 'vue-router'
import store from './store'

/* Guest Component */
const Login = () => import('/src/components/Login.vue')
const Register = () => import('/src/components/Register.vue')
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('/src/components/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('/src/components/Dashboard.vue')
/* Authenticated Component */
const User = () => import('/src/components/User.vue')


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    },
    {
        path: '/user/:id',
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "user",
                path: '/user/:id',
                component: User,
                meta: {
                    title: `User`
                }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router