<template>
    <div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-4">
                <h1>Puestos en el Parking</h1>
                <div class="d-flex justify-content-center mt-3">
                    <EntryCar v-if="cars.length > 0 && parking_spots_enabled.length > 0" :cars="cars" :spots_enabled="parking_spots_enabled" @fetch_cars="getCars" class="me-3" />

                    <RetryCar v-if="parking_spots_disabled.length>0" :spots_disabled="parking_spots_disabled" @fetch_cars="getCars"/> 
                </div>
                
            </div>
        </div>
            
        <SearchComponent @search="searchSpots"/> 
        <div class="row">
            <div v-for="spot in parking_spots" :key="spot.spot_code" class="col-md-3">
                <ParkingSpot :spot="spot" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onMounted, computed, ref } from 'vue';
    import { useStore } from 'vuex';
    import ParkingSpot from './ParkingSpot.vue';
    import EntryCar from './EntryCar.vue';
    import RetryCar from './RetryCar.vue'
    import SearchComponent from './SearchComponent.vue';
    const store = useStore();
    const parking_spots = computed(() => store.getters['parking_spotsModule/getParkingSpots']);
    const parking_spots_enabled = computed(() => store.getters['parking_spotsModule/getParkingSpotsEnabled']);
    const cars = computed(()=>   store.getters['carsModule/getCars']);
    const parking_spots_disabled = computed(() => store.getters['parking_spotsModule/getParkingSpotsDisabled']);
    onMounted(async () => {
        await getSpots();
        await getCars();
    });
    const getCars = async (search = null) =>{
        await store.dispatch('carsModule/fetchCars',search);
    }
    const getSpots = async (search = null) => {
        await store.dispatch('parking_spotsModule/fetchParkingSpots', search);
    };

    const searchSpots = async (searchTerm) => {
        await getSpots(searchTerm);
    };
</script>
