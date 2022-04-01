
import Home from "./pages/Home";
import Books from "./pages/Books";
import Login from "./pages/Login";
import CodeVerification from "./pages/CodeVerification";

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
        path: '/code_verification',
        component: CodeVerification
    }
]