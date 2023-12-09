<template>
        <nav class="navbar navbar-expand-lg bg-body-tertiary header-car ">
                <div class="container-fluid">
                    <div class="dropdown">
                        <a
                            v-show="username != null"
                            class="navbar-brand item-header dropdown-toggle"
                            href="#"
                            role="button"
                            id="userDropdown"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            ULEAM <strong>{{ username?.dni }} {{ username?.name }}</strong>
                        </a>
                        <!-- Menú desplegable -->
                        <div class="dropdown-menu diosito" aria-labelledby="userDropdown">
                            <router-link to="/dashboard-car" class="dropdown-item">
                                <svg class="bi bi-house-add mb-2 " xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                </svg>
                                Dashboard Parking
                            </router-link>
                            <router-link v-if="username?.type_rol === 'SuperAdmin'" to="/register-user" class="dropdown-item">
                                <svg class="bi bi-person-fill-add mb-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
                                </svg>
                                Registrar Usuario
                            </router-link>
                            <router-link v-if="username?.type_rol === 'SuperAdmin'" to="/history-parking" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                                    <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8m0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/>
                                </svg>
                                Historal Parking
                            </router-link>
                            <a class="dropdown-item" href="#" @click="logout">Cerrar Sesión</a>
                        </div>
                    </div>
                    <div v-if = "username === null" class="nav-item text-dark dropdown mt-2">
                        <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="bi bi-person-fill-gear mx-auto mb-1"  xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="dark" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Zm9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
                            </svg>
                            Usuario 
                        </a>
                        <ul class="dropdown-menu" data-bs-popper="static">
                            <li>
                            <router-link to="/login-user" class="dropdown-item">
                                <svg class="bi bi-person-fill-up me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="dark" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.354-5.854 1.5 1.5a.5.5 0 0 1-.708.708L13 11.707V14.5a.5.5 0 0 1-1 0v-2.793l-.646.647a.5.5 0 0 1-.708-.708l1.5-1.5a.5.5 0 0 1 .708 0ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
                                </svg>
                                Iniciar Sesión
                            </router-link>
                            </li>
                            <li>
                            <router-link to="/" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cone-striped" viewBox="0 0 16 16">
                                <path d="m9.97 4.88.953 3.811C10.159 8.878 9.14 9 8 9c-1.14 0-2.158-.122-2.923-.309L6.03 4.88C6.635 4.957 7.3 5 8 5s1.365-.043 1.97-.12m-.245-.978L8.97.88C8.718-.13 7.282-.13 7.03.88L6.275 3.9C6.8 3.965 7.382 4 8 4c.618 0 1.2-.036 1.725-.098zm4.396 8.613a.5.5 0 0 1 .037.96l-6 2a.5.5 0 0 1-.316 0l-6-2a.5.5 0 0 1 .037-.96l2.391-.598.565-2.257c.862.212 1.964.339 3.165.339s2.303-.127 3.165-.339l.565 2.257 2.391.598"/>
                                </svg>
                                Home
                            </router-link>
                            </li>
                            
                        </ul>
                    </div>
                    <a class="navbar-brand item-header " >{{ currentDate.toLocaleDateString() }}</a>
                    <a class="navbar-brand item-header " >{{ currentDate.toLocaleTimeString() }}</a>
                </div>
            </nav>

</template>

<script setup>
    defineProps({
        username: Object
    })
    import { ref, onMounted } from 'vue';
    const emits = defineEmits(['logout']);
    const currentDate = ref(new Date());
    onMounted(() => {
        setInterval(() => {
            currentDate.value = new Date();
        }, 1000);
    });
    const logout = async() =>{
        emits('logout');
    }
</script>
<style scoped>
    .header-car{
        background-color: rgb(125, 184, 123) !important;
    }
    .border_r{
        border: 5px solid red;
    }
    .border_b{
        border: 5px solid blue;
    }

    .diosito{
        position: absolute;
        left: 50px;
        right: 18px;
    }
</style>