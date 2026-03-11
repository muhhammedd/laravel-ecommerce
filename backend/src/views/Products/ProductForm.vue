<template>
  <div class="flex items-center justify-between mb-3">
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ product.id ? `Update product: "${product.title}"` : 'Create new Product' }}
    </h1>
  </div>
  <div class="bg-white rounded-lg shadow animate-fade-in-down relative">
    <Spinner v-if="loading"
             class="absolute left-0 top-0 bg-white/50 right-0 bottom-0 flex items-center justify-center z-50"/>
    <form v-if="!loading" @submit.prevent="onSubmit">
      <div class="grid grid-cols-3">
        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput class="mb-2" v-model="product.title" label="Product Title" :errors="errors['title']"/>
          <CustomInput type="richtext" class="mb-2" v-model="product.description" label="Description" :errors="errors['description']"/>
          <CustomInput type="number" class="mb-2" v-model="product.price" label="Price" prepend="$" :errors="errors['price']"/>
          <CustomInput type="number" class="mb-2" v-model="product.quantity" label="Quantity" :errors="errors['quantity']"/>
          <CustomInput type="checkbox" class="mb-2" v-model="product.published" label="Published" :errors="errors['published']"/>
          <div class="mt-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
            <treeselect v-model="product.categories" :multiple="true" :options="options" :errors="errors['categories']"/>
            <small v-if="errors['categories']" class="text-red-600">{{ errors['categories'][0] }}</small>
          </div>
        </div>
        <div class="col-span-1 px-4 pt-5 pb-4">
          <image-preview v-model="product.images"
                         :images="product.images"
                         v-model:deleted-images="product.deleted_images"
                         v-model:image-positions="product.image_positions"/>
        </div>
      </div>
      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="submit"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save
        </button>
        <button type="button"
                @click="onSubmit($event, true)"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save & Close
        </button>
        <router-link :to="{name: 'app.products'}"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                     ref="cancelButtonRef">
          Cancel
        </router-link>
      </footer>
    </form>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import {useRoute, useRouter} from "vue-router";
import ImagePreview from "../../components/ImagePreview.vue";
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'
import axiosClient from "../../axios.js";

// Define props to handle extraneous attributes like 'id'
defineProps({
  id: [String, Number]
})

const route = useRoute()
const router = useRouter()

const product = ref({
  id: null,
  title: '',
  images: [],
  deleted_images: [],
  image_positions: {},
  description: '',
  price: 0,
  quantity: 0,
  published: false,
  categories: []
})

const errors = ref({});
const loading = ref(false)
const options = ref([])

onMounted(() => {
  if (route.params.id) {
    loading.value = true
    store.dispatch('getProduct', route.params.id)
      .then((response) => {
        loading.value = false;
        const data = response.data;
        // Ensure null values are converted to appropriate defaults for components
        product.value = {
          id: data.id,
          title: data.title || '',
          description: data.description || '',
          price: data.price || 0,
          quantity: data.quantity || 0,
          published: !!data.published,
          categories: data.categories || [],
          images: data.images || [],
          deleted_images: [],
          image_positions: data.image_positions || {}
        };
      })
      .catch(() => {
        loading.value = false;
      })
  }

  axiosClient.get('/categories/tree')
    .then(result => {
      options.value = result.data
    })
})

function onSubmit($event, close = false) {
  loading.value = true
  errors.value = {};
  
  // Create a clean payload for submission
  const p = product.value;
  const payload = {
    ...p,
    title: p.title || '',
    description: p.description || '',
    price: p.price || 0,
    quantity: p.quantity || 0,
    published: p.published ? 1 : 0
  };

  const action = payload.id ? 'updateProduct' : 'createProduct';

  store.dispatch(action, payload)
    .then(response => {
      loading.value = false;
      if (response.status === 200 || response.status === 201) {
        store.commit('showToast', `Product was successfully ${payload.id ? 'updated' : 'created'}`);
        store.dispatch('getProducts')
        if (close) {
          router.push({name: 'app.products'})
        } else if (!payload.id) {
          router.push({name: 'app.products.edit', params: {id: response.data.id}})
        } else {
          // Update local state with fresh data from server
          const data = response.data;
          product.value = {
            ...product.value,
            ...data,
            description: data.description || '',
            deleted_images: []
          };
        }
      }
    })
    .catch(err => {
      loading.value = false;
      if (err.response && err.response.data && err.response.data.errors) {
        errors.value = err.response.data.errors
      }
    })
}
</script>
