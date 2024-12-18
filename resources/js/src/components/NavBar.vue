
<script lang="ts" setup>
  import { onMounted, ref } from "vue";
  import { RouterLink } from "vue-router";
  import { appBase } from "../app/appBase";
  import { getUserData } from "@/helper/getUserData"


  const emit=defineEmits<{
      (e:'logout'):Promise<void>,
  }>()

  defineProps<{
      loggedInUserEmail:string|undefined
  }>()
  
  const email = ref("");
  const id = ref("");

  onMounted(async () => {
    const data = getUserData();
    if (data == null) return;
    email.value = data.user.email;
    id.value = data.user.id;
  })
</script>
<template>
  <nav class="bg-white dark:bg-gray-900 w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="flex flex-wrap items-center justify-between mx-auto px-4 py-2">
      <div class="flex gap-5">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
          <span class="self-center text-base font-semibold whitespace-nowrap dark:text-white bg-white bg-opacity-10 px-2 py-1 rounded-md">Zekko</span>
        </a>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
          <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium text-sm border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            <li>
              <RouterLink to="/project" class="block py-2 px-3 text-white rounded md:bg-transparent md:hover:text-blue-500 md:p-0" aria-current="page">Home</RouterLink>
            </li>
            <li>
              <RouterLink to="/project" class="block py-2 px-3 text-white rounded md:bg-transparent md:hover:text-blue-500 md:p-0" aria-current="page">About</RouterLink>
            </li>
          </ul>
        </div>
      </div>
      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <!-- <div class="relative w-8 h-8 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
            <svg class="absolute w-10 h-10 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
        </div> -->
        <RouterLink :to="'/profile/' + id">
          <div
              class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-gray-200 rounded-full"
          >
              <span class="font-medium text-sm text-gray-600 pointer-events-none">{{ email[0] }}</span>
          </div>
        </RouterLink>
        <!-- <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Get started</button> -->
        <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
          </svg>
        </button>
      </div>
    </div>
  </nav>
</template>