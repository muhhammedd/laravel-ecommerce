<template>
  <div class="flex justify-center items-center min-h-full w-full min-w-full">
    <div class="flex">
      <div class="text-amber-700 self-start text-5xl px-8 font-bold border-r pb-8 leading-10">404</div>
      <div class="px-8">
        <h1 class="text-5xl font-bold mb-2">Page Not Found</h1>
        <p class="text-gray-400  mb-10">Please check the url in address bar and try again</p>
        <div>
          <router-link :to="{name: 'app.dashboard'}"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-amber-700 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            Go back home
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

</script>

<style scoped>

</style>
