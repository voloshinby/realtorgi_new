<template>
  <div class="container mx-auto text-center">
    <div
      class="flex flex-col items-center justify-center text-center text-gray-700"
    >
      <span
        class="flex items-center justify-center w-32 h-32 m-6 bg-gray-200 rounded-full"
      >
        <alert-triangle-icon class="w-16 h-16" />
      </span>
      <h1 class="text-6xl">{{ error.statusCode }}</h1>
      <p class="text-2xl text-gray-600">{{ error.message }}</p>
      <nuxt-link
        :to="'/'"
        class="inline-flex px-4 py-2 mt-8 text-sm font-semibold text-black transition duration-150 bg-white border-2 rounded-lg shadow-sm hover:text-black hover:bg-gray-200 focus:border-gray-600 focus:outline-none"
      >
        <arrow-left-icon class="flex-shrink w-5 h-5 mr-2" />
        На главную
      </nuxt-link>
    </div>
  </div>
</template>
<script>
import { ArrowLeftIcon, AlertTriangleIcon } from 'vue-feather-icons'
export default {
  components: { ArrowLeftIcon, AlertTriangleIcon },
  props: ['error'],
}
</script>
