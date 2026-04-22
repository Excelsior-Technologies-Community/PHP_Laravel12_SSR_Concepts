<template>
  <div>
    <h1>Posts</h1>

    <input v-model="search" placeholder="Search posts..." />

    <div v-for="post in filteredPosts" :key="post.id">
      <h3>{{ post.title }}</h3>
      <p>{{ post.content }}</p>

      <button @click="toggleFavorite(post.id)">
        ❤️ Favorite
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
  posts: Array
})

const search = ref('')

const filteredPosts = computed(() => {
  return props.posts.filter(p =>
    p.title.toLowerCase().includes(search.value.toLowerCase())
  )
})

const toggleFavorite = async (id) => {
  await axios.post(`/api/posts/${id}/favorite`)
  alert('Updated favorite')
}
</script>