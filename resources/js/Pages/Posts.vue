<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
      
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Manage Posts</h1>
        <div class="relative">
          <input 
            v-model="search" 
            @input="performSearch" 
            placeholder="Search posts..." 
            class="pl-4 pr-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-indigo-500 outline-none w-64 transition" 
          />
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <h2 class="text-xl font-bold mb-4 text-gray-800">{{ editingId ? 'Edit Post' : 'Add New Post' }}</h2>
        <div class="space-y-4">
          <input 
            v-model="form.title" 
            placeholder="Post Title" 
            class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none"
          >
          <textarea 
            v-model="form.content" 
            placeholder="Write something amazing..." 
            rows="3"
            class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none"
          ></textarea>
          <div class="flex gap-3">
            <button 
              @click="savePost" 
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-semibold transition"
            >
              {{ editingId ? 'Update Post' : 'Create Post' }}
            </button>
            <button 
              v-if="editingId" 
              @click="resetForm" 
              class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-xl font-semibold transition"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <div v-for="post in posts" :key="post.id" 
             class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition flex justify-between items-start group">
          <div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ post.title }}</h3>
            <p class="text-gray-600 text-sm leading-relaxed">{{ post.content }}</p>
          </div>
          
          <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
            <button @click="toggleFavorite(post.id)" class="p-2 text-red-400 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Favorite">❤️</button>
            <button @click="editPost(post)" class="p-2 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition" title="Edit">✏️</button>
            <button @click="deletePost(post.id)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Delete">🗑️</button>
          </div>
        </div>

        <div v-if="posts.length === 0" class="text-center py-12 text-gray-400">
          No posts found. Create your first one!
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const search = ref('');
const form = ref({ title: '', content: '' });
const editingId = ref(null);

const fetchPosts = async () => {
  const response = await axios.get('/api/posts');
  posts.value = response.data;
};

const performSearch = async () => {
  if (search.value === '') { fetchPosts(); return; }
  const response = await axios.get(`/api/posts/search?search=${search.value}`);
  posts.value = response.data.data;
};

const savePost = async () => {
  if (editingId.value) {
    await axios.put(`/api/posts/${editingId.value}`, form.value);
  } else {
    await axios.post('/api/posts', form.value);
  }
  resetForm();
  fetchPosts();
};

const editPost = (post) => {
  editingId.value = post.id;
  form.value = { title: post.title, content: post.content };
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deletePost = async (id) => {
  if (confirm('Are you sure you want to delete this?')) {
    await axios.delete(`/api/posts/${id}`);
    fetchPosts();
  }
};

const toggleFavorite = async (id) => {
  await axios.post(`/api/posts/${id}/favorite`);
  alert('Favorite status updated!');
};

const resetForm = () => {
  form.value = { title: '', content: '' };
  editingId.value = null;
};

onMounted(fetchPosts);
</script>