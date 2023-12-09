<template>
    <div>
        <form method="GET" @submit.prevent="Search" class="d-flex mt-3">
            <div class="input-group search-style">
                <input
                    type="search"
                    class="form-control"
                    placeholder="Buscar..."
                    aria-label="Search"
                    v-model="search"
                />
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </div>
        </form>
    </div>
</template>
<script setup>
    import {ref, watch} from 'vue';
    const search = ref();
    const emit = defineEmits(['search']);
    // Si el componente que contiene el botón no debe ser accesible para usuarios autenticados
    const Search = async() =>{
        emit('search', search.value);
    };
     // Observa cambios en la variable search
    watch(search, (newVal, oldVal) => {
        // Realiza la búsqueda cada vez que search cambie
        // Puedes llamar a la función de búsqueda aquí
        // Llama a tu función de búsqueda aquí
        emit('search', newVal);
    });
</script>
<style scoped>
    .search-style {
    box-sizing: border-box;
    width: 40%; /* Ajusta el ancho según tus necesidades */
    margin: auto; /* Centra horizontalmente */
    }
</style>