<template>
  <Navbar />
  <router-view></router-view>
</template>

<script>
import Navbar from '@/layouts/Navbar';

export default {
  name: 'App',
  components: {
    Navbar,
  }
}
</script>

<style>
</style>
