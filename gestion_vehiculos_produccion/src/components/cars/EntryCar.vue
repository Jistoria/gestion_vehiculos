<template>
    <div>
        <!-- Botón para abrir el modal -->
        <button @click="openModal" class="btn btn-primary entry_car">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
            </svg>
            Ingresar Carro
        </button>
        <!-- Modal -->
        <div class="modal fade" id="entryCarModal" tabindex="-1" aria-labelledby="entryCarModalLabel" aria-hidden="true"  >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="entryCarModalLabel">Ingreso de Carro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Sección de Carros -->
                            <div class="col-md-6">
                                <h4>Carros Sin Ingresar</h4>
                                <div>
                                    <form method="GET" @submit.prevent="" class="d-flex mt-3">
                                        <div class="input-group search-style">
                                            <input
                                                    type="search"
                                                    class="form-control"
                                                    placeholder="Buscar..."
                                                    aria-label="Search"
                                                    @input="searchCars = $event.target.value.toUpperCase()"
                                            />
                                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                                        </div>
                                    </form>
                                </div>
                                <div v-for="(car, index) in cars" :key="car.license_plate">
                                    <input type="checkbox" v-model="selectedCars" :value="car.license_plate" />
                                    {{ car.license_plate }} - {{ car.brand }}
                                </div>
                            </div>

                            <!-- Sección de Puestos Disponibles -->
                            <div class="col-md-6">
                                <h4>Puestos Disponibles</h4>
                                <div>
                                    <form method="GET" @submit.prevent="" class="d-flex mt-3">
                                        <div class="input-group search-style">
                                            <input
                                                    type="search"
                                                    class="form-control"
                                                    placeholder="Buscar..."
                                                    aria-label="Search"
                                                    @input="searchSpots = $event.target.value.toUpperCase()"
                                            />
                                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                                        </div>
                                    </form>
                                </div>
                                <div v-for="(spot, index) in spots_enabled" :key="spot.spot_code">
                                    <input type="checkbox" v-model="selectedSpots" :value="spot.spot_code" />
                                    {{ spot.spot_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="submitEntry" data-bs-dismiss="modal">Ingresar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import { useStore } from 'vuex';
    const props = defineProps(['cars', 'spots_enabled']);
    const selectedCars = ref([]);
    const selectedSpots = ref([]);
    const searchSpots = ref();
    const searchCars = ref();
    const store = useStore();
    const emits = defineEmits(['fetch_cars']);
    // Función para abrir el modal
    const openModal = () => {
        const modal = new bootstrap.Modal(document.getElementById('entryCarModal'));
        modal.show();
    };
    const submitEntry = async () => {
        if (selectedCars.value.length > 0 && selectedSpots.value.length > 0) {
            // Realizar aquí la lógica para procesar el ingreso
            if(selectedCars.value.length >1 || selectedSpots.value.length >1){
                alert('Solo se debe escoger un carro y un puesto gil');
                return;
            }
            try{
                const data = {
                    selectedCars: selectedCars.value,
                    selectedSpots: selectedSpots.value
                }
                store.dispatch('parking_spotsModule/reserveParkingSpot',data);
                // Cerrar el modal después de procesar con éxito
                store.dispatch('carsModule/fetchCars');  // Reemplaza esto con tu método real
                
            }catch{

            }
            // Limpia la selección después de procesar
            selectedCars.value = [];
            selectedSpots.value = [];
        } else {
            alert('Debes seleccionar al menos un carro y un puesto');
        }
    };
    
    const cars = ref(props.cars);
    const spots_enabled = ref(props.spots_enabled);

    
    watch(searchCars, (newVal, oldVal) => {
        // Realiza la búsqueda cada vez que search cambie
        // Puedes llamar a la función de búsqueda aquí
        // Llama a tu función de búsqueda aquí
        filterCars(newVal);
    });
    watch(searchSpots, (newVal, oldVal) => {
        // Realiza la búsqueda cada vez que search cambie
        // Puedes llamar a la función de búsqueda aquí
        // Llama a tu función de búsqueda aquí
        filterSpots(newVal);
    });

    const filterCars = (search) => {
    try {
        cars.value = search !== '' ? props.cars.filter(car => car.license_plate.includes(search)) : props.cars;
    } catch (error) {
        console.error('Error in filterCars:', error);
    }
};

const filterSpots = (search) => {
    try {
        spots_enabled.value = search !== '' ? props.spots_enabled.filter(spot => spot.spot_code.includes(search)) : props.spots_enabled;
    } catch (error) {
        console.error('Error in filterSpots:', error);
    }
};
    
    // Función para procesar el ingreso
    

</script>
<style scoped>
.entry_car {
    background-color: rgb(124, 172, 172) !important;
    border: none;
    font-size: medium;
    transition: background-color 0.3s ease; /* Agrega una transición suave */
}

.entry_car:active {
    background-color:rgb(161, 226, 165); /* Reemplaza 'nuevo-color' con el color deseado */
}
</style>
