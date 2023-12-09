<template>
    <div class="d-flex justify-content-center">

        <form @submit.prevent="userRegister" class="register_pre" id="registerForm">
            <h4 class="text-center">Registro de usuario</h4>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI:</label>
                <input type="text" class="form-control" v-model="newUser.dni" required :maxlength="10" />
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" class="form-control" v-model="newUser.name" required :maxlength="15"/>
            </div>
    
            <div class="mb-3">
                <label for="lastname" class="form-label">Apellido:</label>
                <input type="text" class="form-control" v-model="newUser.lastname" required :maxlength="15"/>
            </div>
    
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" v-model="newUser.password" required :minlength="9"/>
            </div>
    
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                <input type="password" class="form-control" v-model="newUser.confirm_password" required />
            </div>
    
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</template>
  
<script setup>
    import {ref, computed, watch} from 'vue';
    import Swal from 'sweetalert2';
    import { useStore } from 'vuex';
    const store = useStore();
    const newUser = ref({
        dni:'',
        name: '',
        lastname: '',
        password: '',
        confirm_password: '',
    });
    const userRegister = async () => {
        // Arreglo para almacenar mensajes de validación
        const validationMessages = [];
        const cedulaError = validateCedula();
        if (cedulaError) {
            validationMessages.push(cedulaError);
        }
        // Validación de coincidencia de contraseñas
        if (newUser.value.password !== newUser.value.confirm_password) {
        validationMessages.push('Las contraseñas no coinciden');
        }

        // Validación de longitud de contraseña
        if (newUser.value.password.length < 9) {
        validationMessages.push('La contraseña es muy corta, debe tener al menos 9 caracteres');
        }


        // Mostrar SweetAlert si hay mensajes de validación
        if (validationMessages.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Errores en los datos',
            html: validationMessages.map(message => `<p>${message}</p>`).join(''),
            confirmButtonText: 'OK'
        });
        return;  // Terminar la función si hay errores de validación
        }
        try{
            const response = await store.dispatch('userModule/userRegister', newUser.value);
            if(response.success){
                console.log(response);
                const Toast = Swal.mixin({
                            toast: true,
                            position: "top-start",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                    });
                Toast.fire({
                    icon: "success",
                    title: response.message,
                });
            }else{
                console.log(response);
                const Toast = Swal.mixin({
                            toast: true,
                            position: "top-start",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "error",
                            title: response.error,
                        });
            }
        }catch{
        }

    };
    const validateCedula = () => {
        const cedula = newUser.value.dni.trim();
        
        if (!/^[0-9]{10}$/.test(cedula)) {
            return 'La cédula debe contener exactamente 10 dígitos';
        }

        const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2, 1];
        let suma = 0;

        for (let i = 0; i < 10; i++) {
            let digito = parseInt(cedula[i], 10);
            digito *= coeficientes[i];
            suma += (digito > 9) ? digito - 9 : digito;
        }

        if (suma % 10 !== 0) {
            return 'La cédula no es válida';
        }

        return null; // La cédula es válida
    };

</script>
<style>
    #registerForm {
        margin: 20px; /* Ajusta el valor según lo necesites */
    }
    .register_pre{
        padding: 15px;
        width: 500px;
        height: auto;
        border: 3px solid rgba(255, 0, 0, 0.272);
        border-radius: 5px;
       
    }
    /* Estilos para reducir el ancho del input de color */
    .color-register-user input[type="color"] {
        width: 50px; /* Ajusta el valor según lo necesites */
    }


</style>