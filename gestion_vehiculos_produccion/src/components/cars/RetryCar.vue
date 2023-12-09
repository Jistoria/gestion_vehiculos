<template>
    <div>
        <!-- Botón para abrir el modal -->
        <button @click="openModal" class="btn btn-primary retry_car">Retirar Carro
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg>
        </button>
        <div class="modal fade" id="retryCarModal" tabindex="-1" aria-labelledby="retryCarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="retryCarModalLabel">Retirar Carro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenido del modal -->
                    <div>
                    <!-- Lista de puestos ocupados -->
                    <h4>Puestos Ocupados</h4>
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
                    <div class="mt-2" v-for="(spot, index) in spots_disabled" :key="spot.spot_code">
                        <div class="matenme text-center">{{ spot.spot_code }} - {{ spot.license_plate }}</div>
                        <!-- Agrega un bot para retirar el carro asociado al puesto -->
                        <button class="btn btn-retirar ms-3" @click="retryCarro(spot)" data-bs-dismiss="modal">Retirar Carro</button>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, watch } from 'vue';
    import { useStore } from 'vuex';
    const store = useStore();
    const props = defineProps(['spots_disabled']);
    const spots_disabled = ref(props.spots_disabled);
    const searchSpots = ref();
    const emits = defineEmits(['fetch_cars']);
    const openModal = () => {
        const modal = new bootstrap.Modal(document.getElementById('retryCarModal'));
        modal.show();
    };
    watch(searchSpots, (newVal, oldVal) => {
        // Realiza la búsqueda cada vez que search cambie
        // Puedes llamar a la función de búsqueda aquí
        // Llama a tu función de búsqueda aquí
        filterSpots(newVal);
    });

    const filterSpots = (search) => {
        spots_disabled.value = search !='' ? props.spots_disabled.filter(spot => spot.spot_code.includes(search)) : props.spots_disabled.slice(0, visibleSpots.value.length);
    };

    const retryCarro = async(spot)=>{
        store.dispatch('parking_spotsModule/releaseParkingSpot', spot.spot_code);
        emits('fetch_cars');
    }
</script>
<style scoped>
    .retry_car{
        background-color: rgb(245, 137, 110);
        border: none;
        font-size: medium;
        transition: background-color 0.3s ease; 
    }
    .retry_car:active{
        background-color: rgb(248, 197, 121);
    }
    .btn-retirar{
        background-color: rgb(233, 202, 161);
        border: none;
        font-size:small;
    }
    .matenme{
        display: inline-block;
        /* border: 1px solid red; */
        width: 180px !important;
        height: 20px !important;

    }
</style>