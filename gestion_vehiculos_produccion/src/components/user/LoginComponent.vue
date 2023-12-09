
<template>
    <div class="mt-5 login-container">
      <form @submit.prevent="userLogin" class="mt-4">
        <h1>Login</h1>
        <div class="mb-3">
          <label for="dni" class="form-label">DNI:</label>
          <input type="text" class="form-control" v-model="user.dni" required :maxlength="10" />
        </div>
  
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña:</label>
          <input type="password" class="form-control" v-model="user.password" required />
        </div>
  
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  const store = useStore();
  const user = ref({
    dni: '',
    password: ''
  });
  
  const userLogin = async () => {
    if (user.value.password === '' || user.value.dni === '') {
      alert('Llene los datos por favor');
      return;
    }
  
    try {
      const response = await store.dispatch('userModule/userLogin', user.value);
      if (response) {
        router.push('/');
      } 
    } catch (error) {
      console.error('Error al iniciar sesión:', error);
      alert('Ocurrió un error al iniciar sesión. Por favor, inténtelo de nuevo.');
    }
  };
  </script>
  <style scoped>
  .login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .form-label {
    font-weight: bold;
  }
  
  .btn-primary {
    background-color: rgb(127, 185, 125);
    width: 100%;
    border: none;
  }
  </style>