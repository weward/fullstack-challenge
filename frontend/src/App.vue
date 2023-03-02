<script setup lang="ts">
import { reactive } from 'vue'
import { RouterLink, RouterView } from "vue-router";
import Menubar from 'primevue/menubar';

const obj = reactive({
  items: [
    {
      link: '/',
      label: 'Home',
    }
  ]
})

</script>

<template>
  <header>
    <div class="wrapper w-80 m-auto mb-60" >
      
      <Menubar :model="obj.items" >
        <template #start>
          <span class="nav-logo">Fullstack <span class="nav-logo-bottom">Challenge</span></span>
        </template>
      
        <template #item="{item}">
          <div class="">
            <RouterLink :to="item.link" class="nav-label">{{item.label}}</RouterLink>
          </div>
        </template>
      
      </Menubar>
    </div>
  </header>
  <RouterView />
</template>

<style scoped>

</style>
