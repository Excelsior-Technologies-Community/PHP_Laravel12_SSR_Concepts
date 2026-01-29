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






















<!-- <template>
  <div>
    <h1>{{ data.title }}</h1>
    <p>{{ data.message }}</p>
  </div>
</template>

<script setup>
import { ref, onServerPrefetch } from 'vue'
import axios from 'axios'

const data = ref({})

const fetchData = async () => {
    const res = await axios.get('http://127.0.0.1:8000/api/home')
    data.value = res.data
}

onServerPrefetch(fetchData)
</script> -->
