<template>
  <home-lots/>
</template>

<script>
import HomeLots from "~/components/partials/home/HomeLots";

export default {
  components: {
    HomeLots
  },
  transition: 'slide-right',
  head() {
    return {
      title: 'Аукционы',
    }
  },
}
</script>
