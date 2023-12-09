import Swal from 'sweetalert2';
import axios from 'axios';

const carsModule = {
    namespaced: true,
    state: {
        cars: [],
    },
    mutations: {
        SET_CARS(state, cars) {
        state.cars = cars;
        },
    },
    actions: {
        async fetchCars({ commit }, search = null) {
            try {
                const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/CarsController.php?get=getCars'); // ajusta la ruta según tu configuración
                commit('SET_CARS', response.data.cars);
            } catch (error) {
                console.error('Error al obtener la lista de puestos:', error);
            }
        },
    },
    getters:{
        getCars: state => state.cars
    }
};

export default carsModule;
