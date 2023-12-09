import Swal from 'sweetalert2';
import axios from 'axios';

const parking_spotsModule = {
    namespaced: true,
    state: {
        parkingSpots: [],
        parkingHistory: []
    },
    mutations: {
        SET_PARKING_SPOTS(state, spots) {
        state.parkingSpots = spots;
        },
        SET_HISTORY_PARKING(state, history){
          state.parkingHistory = history;
        }
    },
actions: {
    async fetchParkingSpots({ commit }, search = null) {
        try {
            let url = 'https://sisti.tech/5b232/g4/gestion_vehiculos/backend/parking-spotsController.php?get=getPS';
        
            if (search) {
                // Si hay un término de búsqueda, añádelo a la URL
                url += `&search=${encodeURIComponent(search)}`;
            }

        const response = await axios.get(url);
            commit('SET_PARKING_SPOTS', response.data.parking_spots);
        } catch (error) {
            console.error('Error al obtener la lista de puestos:', error);
        }
    },
    async reserveParkingSpot({ dispatch }, data) {
        try {
            const response = await axios.post('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/parking-spotsController.php?post=entryCar', data, {
              withCredentials : true
          });
            dispatch('fetchParkingSpots');
            Swal.fire({
                icon: 'success',
                title: 'Puesto reservado exitosamente',
            });
        } catch (error) {
            console.error('Error al reservar el puesto:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error al reservar el puesto',
            });
        }
    },
    async releaseParkingSpot({ dispatch }, spotId) {
      try {
        let spot_code = JSON.stringify(spotId);
        const response = await axios.put('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/parking-spotsController.php?put=releaseCar',spot_code,{
          withCredentials:true
        });
        dispatch('fetchParkingSpots');
        Swal.fire({
          icon: 'success',
          title: 'Puesto liberado exitosamente',
        });
      } catch (error) {
        console.error('Error al liberar el puesto:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error al liberar el puesto',
        });
      }
    },
    async historyParkingSpot({commit}){
      try{
        const response = await axios.get('https://sisti.tech/5b232/g4/gestion_vehiculos/backend/parking-spotsController.php?get=history',{
          withCredentials:true
        });
        commit('SET_HISTORY_PARKING',response.data.history);
      }catch{

      }
      
    }
  },
  getters:{
    getParkingSpots: state => state.parkingSpots,
    getParkingSpotsEnabled: state => state.parkingSpots.filter(spot => spot.status === 'Disponible'),
    getParkingSpotsDisabled: state => state.parkingSpots.filter(spot => spot.status === 'Ocupado')
  }

};

export default parking_spotsModule;
