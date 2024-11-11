<script setup lang="ts">
  import { onMounted } from 'vue';
  import NavBar from '../../components/NavBar.vue'
  import { getUserData } from '../../helper/getUserData';
  import { useLogoutUser } from './action/Logout'
  import utility from '../../helper/utility';

  const { logout } = useLogoutUser()
  const userData = getUserData()
  async function logoutUser() {
    const userId = userData?.user?.id
    if (typeof userId !== 'undefined') {
      await logout(userId)
      localStorage.clear()
      setTimeout(() => window.location.href="/app/login", 1000)
    }
  }

  onMounted(async ()=>{
    if (localStorage.getItem('userData') === null) {
      window.location.href = '/login'
      utility.showErrorMessage("Login to continue")
    }
  })
</script>

<template>
  <div class="">
    <NavBar @logout="logoutUser" :logged-in-user-email="userData?.user?.email" />
    <div class="">
      <div class="p-4 sm:ml-64 h-screen">
        <router-view v-slot="{ Component, route }">
          <!-- <transition name="fade" mode="out-in"> -->
            <div class="h-full" :key="route.name">
              <component :is="Component"></component>
            </div>
          <!-- </transition> -->
        </router-view>
      </div>
    </div>
  </div>

</template>