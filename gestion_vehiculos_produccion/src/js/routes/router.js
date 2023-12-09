// routes/router.js

import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
// Importa los componentes
import HomeComponent from '../../components/HomeComponent.vue';
import RegisterUserComponent from '../../components/admin/RegisterUserComponent.vue';
import LoginComponent from '../../components/user/LoginComponent.vue';
import DashboardCar from '../../components/cars/DashboardCar.vue'
import NotFoundComponent from '../../components/NotFoundComponent.vue'
import ParkingHistory from '../../components/admin/ParkingHistory.vue'
// import axios from 'axios';
// import Reservaciones from '../components/sections/Reservaciones.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomeComponent,
        meta: {
            requiresVisitor: true
        }
    },
    //solo un superAdministrador puede registrar usuarios
    {
        path: '/register-user',
        name: 'RegisterUser',
        component: RegisterUserComponent,
        meta: { requiresSuperAdmin: true }
    },
    {
        path: '/history-parking',
        name: 'HistoryParking',
        component: ParkingHistory,
        meta: { requiresSuperAdmin: true }
    },
    {
        path: '/login-user',
        name: 'LoginUser',
        component: LoginComponent,
        meta: {
            requiresVisitor: true
        }
    },
    {
        path:'/dashboard-car',
        name: 'DashboardCar',
        component: DashboardCar,
        meta:{
            requiresAuth: true
        }
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFoundComponent, // Debes definir un componente NotFound
    },
];

const router = createRouter({
    history: createWebHistory('/5b232/g4/gestion_vehiculos/'),
    routes
});


router.beforeEach(async (to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const requiresSuperAdmin = to.matched.some(record => record.meta.requiresSuperAdmin);
    const requiresVisitor = to.matched.some(record => record.meta.requiresVisitor);
  
        // Lógica para verificar la autenticación...
        if (requiresAuth) {
        try {
            const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?get=getSession', {
            withCredentials: true
            });
            if (response.data.success) {
            next(); // Usuario autenticado, permite el acceso
            } else {
            next('/login-user'); // Usuario no autenticado, redirige a la página de inicio de sesión
            }
        } catch (error) {
            console.error(error);
            next('/login-user'); // Si hay un error en la petición, redirige a la página de inicio de sesión
        }
        }
    
        // Lógica para verificar si es superadministrador...
        if (requiresSuperAdmin) {
        try {
            const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?get=isSuperAdmin', {
            withCredentials: true
            });
            const isAdmin = response.data.isAdmin;
            if (isAdmin) {
            next(); // Usuario es superadministrador, permite el acceso
            } else {
            next('/'); // Usuario no es superadministrador, redirige a la página principal
            }
        } catch (error) {
            console.error(error);
            next('/login-user'); // Si hay un error en la petición, redirige a la página de inicio de sesión
        }
        }
    
        // Lógica para verificar si el usuario es visitante...
        if (requiresVisitor) {
        try {
            const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?get=getSession', {
            withCredentials: true
            });
            if (!response.data.success) {
                next(); // Usuario autenticado, redirige a la página principal
            } else {
                next('/dashboard-car'); // Usuario no autenticado, permite el acceso
            }
        } catch (error) {
            console.error(error);
            next(); // Si hay un error en la petición, permite el acceso (puede ajustarse según tus requisitos)
        }
    }
});



export default router;
