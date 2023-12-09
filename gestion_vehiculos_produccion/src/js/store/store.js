import { createStore } from 'vuex';
import userModule from './module/userModule';
import parking_spotsModule from './module/parkingSpotsModule';
import carsModule from './module/carsModule';
const store = createStore({
    modules: {
        userModule,
        parking_spotsModule,
        carsModule
    }
});

export default store;