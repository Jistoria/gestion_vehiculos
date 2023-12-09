<template>
    <div>
      <h1>Historial de Parking</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Matrícula</th>
            <th>Código de Puesto</th>
            <th>Fecha y Hora de Ocupación</th>
            <th>Fecha y Hora de Liberación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="entry in parking_history" :key="entry.license_plate + entry.spot_code + entry.occupied_datetime">
            <td>{{ entry.license_plate }}</td>
            <td>{{ entry.spot_code }}</td>
            <td>{{ formatDateTime(entry.occupied_datetime) }}</td>
            <td v-if="entry.release_datetime">{{ formatDateTime(entry.release_datetime) }}</td>
            <td v-else>Aun es puesto</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { computed, onMounted, ref } from 'vue';
  import { useStore } from 'vuex';
  
  const store = useStore();
  const parking_history = computed(() => store.state.parking_spotsModule.parkingHistory);
  
  onMounted(async () => {
    await store.dispatch('parking_spotsModule/historyParkingSpot');
  });
  
  const formatDateTime = (dateTimeString) => {
    const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
    return new Date(dateTimeString).toLocaleDateString(undefined, options);
  };
  </script>
  
  <style scoped>
    /* Agrega estilos adicionales según sea necesario */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
  
    th, td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
  
    th {
      background-color: #f2f2f2;
    }
  </style>