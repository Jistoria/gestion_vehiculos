import Swal from 'sweetalert2';
import axios from 'axios';
const userModule = {
    namespaced: true,
    state: {
        user: null, // los datos de usuario
        authenticated: false,
    },
    mutations: {
        SET_USER(state, payload) {
        state.user = payload;
        },
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_STYLIST(state, value){
            state.list_user_style = value;
        },
        SET_LIST_RATING_PRODUCT(state, value){
            state.my_product_rating = value;
        },
        ADD_STYLIST(state, payload){
            state.list_user_style.push(payload);
        },
        REMOVE_STYLIST(state, payload){
            state.list_user_style = state.list_user_style.filter(list_user_style => list_user_style.id_user !== payload);
        }
    },
    actions: {
            async userRegister(context, newUser) {
                try {
                    // Realizar una petición para obtener los productos
                    let userNew = JSON.stringify(newUser);
                    const response = await axios.post('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?post=registerUser', userNew );// Asegúrate de cambiar esta URL por la correcta
                    // Llamar a la mutación para actualizar el estado
                    return response.data;
                } catch (error) {
                    console.error(error);
                }
            },
            async userLogin(context, user) {
                try {
                    // Realizar una petición para obtener los productos
                    let userLogin = JSON.stringify(user);
                    const response = await axios.post('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?post=login', userLogin,
                    {
                        withCredentials : true
                    } );// Asegúrate de cambiar esta URL por la correcta
                    if (response.data.success) {
                        // Llamar a la mutación para actualizar el estado
                        context.commit('SET_USER', response.data.user);
                        context.commit('SET_AUTHENTICATED', true); 
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-start",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: "Sesion iniciada"
                        });
                        return response.data.success;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Credenciales inválidas',
                            text: 'Por favor, verifica tus credenciales e inténtalo nuevamente.',
                            confirmButtonText: 'OK'
                        });
                        return response.data.success;
                    }
                } catch (error) {
                    console.error(error);
                }
            },
            async getSession(context) {
                try {
                    // Realizar una petición para obtener los productos
                    const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?get=getSession',
                    {
                        withCredentials : true
                    });                    
                    if (response.data.success) {
                        // Llamar a la mutación para actualizar el estado
                        context.commit('SET_USER', response.data.user);
                        context.commit('SET_AUTHENTICATED', true); 
                    } 
                } catch (error) {
                    console.error(error);
                }
            },
            async userLogout(context) {
                try {
                    
                    const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/UserController.php?get=logout',
                    {
                        withCredentials : true
                    } );// Asegúrate de cambiar esta URL por la correcta
                    if (response.data.success) {
                        // Llamar a la mutación para actualizar el estado
                        context.commit('SET_USER', null);
                        context.commit('SET_AUTHENTICATED', false); 
                    } else {
                        console.log('Error al cerrar sesion');
                    }
                } catch (error) {
                    console.error(error);
                }
            },
    },
    getters: {
        
    }
};


export default userModule;