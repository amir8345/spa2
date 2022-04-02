
import Home from "./pages/Home";
import Books from "./pages/Books";
import Login from "./pages/Login";
import CodeVerification from "./pages/CodeVerification";
import UsernamePassword from "./pages/UsernamePassword";
import PasswordCheck from "./pages/PasswordCheck";
import ForgotPassword from "./pages/ForgotPassword";
import Profile from "./pages/Profile";

export default [
    {
        path: '/',
        component: Home
    },
    {
        path: '/books',
        component: Books
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/login/code_verification',
        component: CodeVerification
    },
    {
        path: '/login/username_password',
        component: UsernamePassword
    },
    {
        path: '/profile',
        component: Profile
    },
    {
        path: '/login/password_check',
        component: PasswordCheck
    },
    {
        path: '/login/forgot_password',
        component: ForgotPassword
    }
]