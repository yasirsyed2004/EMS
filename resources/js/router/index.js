import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

// Layouts
import DefaultLayout from "../layout/MainLayout.vue";

// Views
import Login from "../views/auth/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import EmployeeList from "../views/employees/Index.vue";
import EmployeeCreate from "../views/employees/Create.vue";
import EmployeeEdit from "../views/employees/Edit.vue";
import EmployeeView from "../views/employees/View.vue";
import DepartmentList from "../views/department/Index.vue";
import DepartmentCreate from "../views/department/Create.vue";
import DepartmentEdit from "../views/department/Edit.vue";
import Profile from "../views/auth/Profile.vue";
// import NotFound from '../views/NotFound.vue'

const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { requiresAuth: false },
    },
    {
        path: "/",
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: "/dashboard",
                name: "Dashboard",
                component: Dashboard,
                meta: { title: "Dashboard", role: ["admin"] },
            },
            {
                path: "employees",
                name: "EmployeeList",
                component: EmployeeList,
                meta: { title: "Employees", permissions: ["show_employee"] },
            },
            {
                path: "employees/create",
                name: "EmployeeCreate",
                component: EmployeeCreate,
                meta: {
                    title: "Add Employee",
                    permissions: ["create_employee"],
                },
            },
            {
                path: "employees/:id/edit",
                name: "EmployeeEdit",
                component: EmployeeEdit,
                meta: {
                    title: "Edit Employee",
                    permissions: ["update_employee"],
                },
            },
            {
                path: "employees/:id",
                name: "EmployeeView",
                component: EmployeeView,
                meta: {
                    title: "Employee Details",
                    permissions: ["show_employee"],
                },
            },
            {
                path: "departments",
                name: "DepartmentList",
                component: DepartmentList,
                meta: {
                    title: "Departments",
                    permissions: ["show_department"],
                    role: ["admin"],
                },
            },
            {
                path: "departments/create",
                name: "DepartmentCreate",
                component: DepartmentCreate,
                meta: {
                    title: "Add Department",
                    permissions: ["create_department"],
                },
            },
            {
                path: "departments/:id/edit",
                name: "DepartmentEdit",
                component: DepartmentEdit,
                meta: {
                    title: "Edit Department",
                    permissions: ["update_department"],
                },
            },
            {
                path: "profile",
                name: "Profile",
                component: Profile,
                meta: { title: "My Profile" },
            },
        ],
    },
    // {
    //   path: '/:pathMatch(.*)*',
    //   name: 'NotFound',
    //   component: NotFound
    // }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const requiresAuth = to.matched.some(
        (record) => record.meta.requiresAuth !== false
    );
    const requiredPermissions = to.meta.permissions;
    const allowedRoles = to.meta.role;
    console.log(requiredPermissions);

    document.title = to.meta.title
        ? `${to.meta.title} - Employee Management System`
        : "Employee Management System";

    if (!authStore.isAuthenticated && localStorage.getItem("token")) {
        try {
            // assume you have a fetchUser method
            await authStore.getCurrentUser();
        } catch {
            authStore.logout();
        }
    }

    // Redirect to login if route needs auth and user is not logged in
    if (requiresAuth && !authStore.isAuthenticated) {
        next("/login");
    }
    // Prevent access to login page if already logged in
    else if (to.path === "/login" && authStore.isAuthenticated) {
        next("/");
    }
    // If route requires specific roles
    else if (allowedRoles && allowedRoles.length > 0) {
        // const userRoles = authStore.user?.roles?.map(r => r.name) || []
        const hasRole = allowedRoles.some((role) =>
            authStore.userRoles.some((rol) => rol.name === role)
        );
        console.log(hasRole, "role");
        if (!hasRole) {
            // Redirect non-admins to their profile
            next({ name: "Profile" });
        } else {
            next();
        }
    }
    // If route requires specific permissions
    else if (requiredPermissions && requiredPermissions.length > 0) {
        const hasPermission = requiredPermissions.some((permission) =>
            authStore.userPermissions.some((perm) => perm.name === permission)
        );

        if (!hasPermission) {
            next({ name: "Dashboard" }); // or 'Profile' depending on your flow
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
