import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Tables from "../views/Tables.vue";
import Billing from "../views/Billing.vue";
import VirtualReality from "../views/VirtualReality.vue";
import RTL from "../views/Rtl.vue";
import Profile from "../views/Profile.vue";
import Signup from "../views/Signup.vue";
import Signin from "../views/Signin.vue";
import Login from "../views/auth/Login.vue";
import Register from "../views/auth/Register.vue";
import UserProfile from "../views/examplesApi/UserProfile/EditUserProfile.vue";
import UserManagement from "../views/examplesApi/UserManagement/ListUserPage.vue";
import ForgotPassword from "../views/auth/ForgotPassword.vue";
import ResetPassword from "../views/auth/ResetPassword.vue";
import auth from "../middleware/auth";
import guest from "../middleware/guest";

const routes = [
  {
    path: "/",
    name: "/",
    redirect: "/dashboard",
  },
  {
    path: "/users",
    name: "users",
    component: () => import('../views/pages/user/Index.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/customers",
    name: "customers",
    component: () => import('../views/pages/customer/Index.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/customers/create",
    name: "customers.create",
    component: () => import('../views/pages/customer/Create.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/customers/edit/:id",
    name: "customers.edit",
    component: () => import('../views/pages/customer/Edit.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/merks",
    name: "merks",
    component: () => import('../views/pages/merk/Index.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/merks/create",
    name: "merks.create",
    component: () => import('../views/pages/merk/Create.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/merks/edit/:id",
    name: "merks.edit",
    component: () => import('../views/pages/merk/Edit.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/mobils",
    name: "mobils",
    component: () => import('../views/pages/mobil/Index.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/mobils/create",
    name: "mobils.create",
    component: () => import('../views/pages/mobil/Create.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/mobils/edit/:id",
    name: "mobils.edit",
    component: () => import('../views/pages/mobil/Edit.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/mobils/show/:id",
    name: "mobils.show",
    component: () => import('../views/pages/mobil/Show.vue'),
    meta: {
      // middleware: auth,
    },
  },
  {
    path: "/examples/user-profile",
    name: "User Profile",
    component: UserProfile,
    meta: {
      middleware: auth,
    },
  },
  {
    path: "/examples/user-management/list-users",
    name: "User Managemant",
    component: UserManagement,
    meta: {
      middleware: auth,
    },
  },
  {
    path: "/tables",
    name: "Tables",
    component: Tables,
  },
  {
    path: "/billing",
    name: "Billing",
    component: Billing,
  },
  {
    path: "/virtual-reality",
    name: "Virtual Reality",
    component: VirtualReality,
  },
  {
    path: "/rtl-page",
    name: "RTL",
    component: RTL,
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
  },
  {
    path: "/signin",
    name: "Signin",
    component: Signin,
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
    meta: {
      middleware: guest,
    },
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
    meta: {
      middleware: guest,
    },
  },
  {
    path: "/password/forgot",
    name: "Forgot Password",
    component: ForgotPassword,
    meta: {
      middleware: guest,
    },
  },
  {
    path: "/password/reset",
    name: "Reset Password",
    component: ResetPassword,
    meta: {
      middleware: guest,
    },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  linkActiveClass: "active",
});

// Creates a nextMiddleware() function which not only
// runs the default next() callback but also triggers
// the subsequent Middleware function.
function nextFactory(context, middleware, index) {
  const subsequentMiddleware = middleware[index];
  // If no subsequent Middleware exists,
  // the default next() callback is returned.
  if (!subsequentMiddleware) return context.next;

  return (...parameters) => {
    // Run the default Vue Router next() callback first.
    context.next(...parameters);
    // Then run the subsequent Middleware with a new
    // nextMiddleware() callback.
    const nextMiddleware = nextFactory(context, middleware, index + 1);
    subsequentMiddleware({ ...context, next: nextMiddleware });
  };
}

router.beforeEach((to, from, next) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware)
      ? to.meta.middleware
      : [to.meta.middleware];
    const context = { from, next, to, router };
    const nextMiddleware = nextFactory(context, middleware, 1);

    return middleware[0]({ ...context, next: nextMiddleware });
  }

  return next();
});

export default router;
