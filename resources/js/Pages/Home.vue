<template>
  <div>
    <h1>{{ title }}</h1>
    <p>{{ message }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  message: String,
})
</script>