export default
{
    path: "/",
    name: "auth",
    component: () => import("@/views/Layouts/AuthLayout.vue"),
    redirect: { name: 'login' },
    children: [
        {
            path: "/login",
            name: "login",
            component: () => import("@/views/Pages/Auth/LoginView.vue"),
        },
        // {
        //     path: "/register",
        //     name: "register",
        //     component: () => import("@/views/Pages/Auth/RegisterView.vue"),
        // },
    ]
};