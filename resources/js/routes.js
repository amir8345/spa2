
import Home from "./components/Home";
import Books from "./components/Books";
import mobileEmail from "./components/signup/mobileEmail";
import codeVerification from "./components/signup/codeVerification";
import setUsernamePassword from "./components/signup/setUsernamePassword";

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
        path: '/signup',
        component: mobileEmail
    },
    {
        path: '/code-verification',
        component: codeVerification
    },
    {
        path: '/set-username-password',
        component: setUsernamePassword
    }
]