<template>
    <HeaderComponent :username="user" @logout="userLogout"/>
    <router-view></router-view>
</template>
<script setup>
  import {onMounted, computed} from 'vue';
  import {useStore} from 'vuex';
  import HeaderComponent from './components/template/HeaderComponent.vue';
  const store = useStore();
  onMounted(async() => {
      await store.dispatch('userModule/getSession');
    });
    const user = computed(() => store.state.userModule.user);
    const authenticated = computed(() => store.state.userModule.authenticated);
    const userLogout = async ()=>{
        await store.dispatch('userModule/userLogout');
    }
</script>