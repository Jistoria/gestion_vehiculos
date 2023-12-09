<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3 text-center">
                <!-- <img src="logo.png" alt="Logo" class="img-fluid mb-4"> -->
                <h1 class="display-4">Bienvenido a la Gestión de Carros ULEAM</h1>
                <p class="lead">Gestiona fácilmente los carros de la Universidad Laica Eloy Alfaro de Manabí.</p>
                <router-link to="/login-user" class="btn btn-primary btn-lg">Iniciar Sesión</router-link> 
            </div>
        </div>
    </div>
</template>

<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
