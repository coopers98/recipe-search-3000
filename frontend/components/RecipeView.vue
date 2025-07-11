<template>
  <div class="container mx-auto p-4">
    <!-- Navigation -->
    <div class="mb-6">
      <button 
        @click="goBack"
        class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4"
      >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Search
      </button>
      
      <h1 class="text-3xl font-bold text-gray-800">Recipe Details</h1>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-2"></div>
      <p class="text-lg">Loading recipe...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
      <h2 class="text-xl font-semibold text-red-800 mb-2">Recipe Not Found</h2>
      <p class="text-red-600 mb-4">{{ error }}</p>
      <button 
        @click="goBack"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
      >
        Back to Search
      </button>
    </div>

    <!-- Recipe Content -->
    <div v-else-if="recipe" class="bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Recipe Header -->
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ recipe.name }}</h2>
        <p class="text-gray-600 text-lg mb-4">{{ recipe.description }}</p>
        
        <!-- Recipe Meta Info -->
        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
          <div v-if="recipe.prep_time_minutes" class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Prep: {{ recipe.prep_time_minutes }} min
          </div>
          <div v-if="recipe.cook_time_minutes" class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
            </svg>
            Cook: {{ recipe.cook_time_minutes }} min
          </div>
          <div v-if="recipe.servings" class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Serves: {{ recipe.servings }}
          </div>
          <div v-if="recipe.difficulty_level" class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            {{ recipe.difficulty_level }}
          </div>
        </div>
        
        <!-- Author -->
        <div class="mt-4 text-sm text-gray-500">
          <span class="font-medium">Author:</span> {{ recipe.author_email }}
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
        <!-- Ingredients Section -->
        <div>
          <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Ingredients
          </h3>
          <div class="bg-gray-50 rounded-lg p-4">
            <ul class="space-y-2">
              <li 
                v-for="(ingredient, index) in recipe.ingredients" 
                :key="index"
                class="flex items-start"
              >
                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                <span class="text-gray-700">
                  <template v-if="typeof ingredient === 'object'">
                    <span v-if="ingredient.quantity" class="font-medium">{{ ingredient.quantity }}</span>
                    <span v-if="ingredient.unit" class="font-medium"> {{ ingredient.unit }}</span>
                    <span class="font-semibold"> {{ ingredient.name }}</span>
                    <span v-if="ingredient.preparation" class="text-gray-600 italic">, {{ ingredient.preparation }}</span>
                  </template>
                  <template v-else>
                    {{ ingredient }}
                  </template>
                </span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Instructions Section -->
        <div>
          <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Instructions
          </h3>
          <div class="space-y-4">
            <div 
              v-for="(step, index) in sortedSteps" 
              :key="index"
              class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500"
            >
              <div class="flex items-start">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">
                  {{ index + 1 }}
                </span>
                <div class="flex-1">
                  <p class="text-gray-700 leading-relaxed">
                    <template v-if="typeof step === 'object'">
                      {{ step.description }}
                    </template>
                    <template v-else>
                      {{ step }}
                    </template>
                  </p>
                  <div v-if="typeof step === 'object' && step.duration_minutes" class="mt-2">
                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      {{ step.duration_minutes }} min
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { API_URL } from '~/config.js'

// Route (still needed to get the slug parameter)
const route = useRoute()

// State
const recipe = ref(null)
const loading = ref(true)
const error = ref(null)

// Computed
const sortedSteps = computed(() => {
  if (!recipe.value || !recipe.value.steps) return []

  // If steps are objects with step_number property, sort by step_number
  if (typeof recipe.value.steps[0] === 'object' && 'step_number' in recipe.value.steps[0]) {
    return [...recipe.value.steps].sort((a, b) => a.step_number - b.step_number)
  }

  // Otherwise, return as is (already in order)
  return recipe.value.steps
})

// Methods
const fetchRecipe = async () => {
  try {
    loading.value = true
    error.value = null
    
    const slug = route.params.slug
    const response = await fetch(`${API_URL}/${slug}`)
    
    if (!response.ok) {
      if (response.status === 404) {
        throw new Error('Recipe not found')
      }
      throw new Error(`Failed to fetch recipe: ${response.status} ${response.statusText}`)
    }
    
    const data = await response.json()
    
    if (!data.data) {
      throw new Error('Invalid response format')
    }
    
    recipe.value = data.data
  } catch (err) {
    console.error('Error fetching recipe:', err)
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  // Always navigate back to the home page to preserve search state
  navigateTo('/')
}

// Lifecycle
onMounted(() => {
  fetchRecipe()
})

// SEO Meta
useHead({
  title: computed(() => recipe.value ? `${recipe.value.name} - Recipe Search 3000` : 'Recipe Details - Recipe Search 3000'),
  meta: [
    {
      name: 'description',
      content: computed(() => recipe.value ? recipe.value.description : 'View detailed recipe information including ingredients and step-by-step instructions.')
    }
  ]
})
</script>

<style scoped>
/* Component-specific styles */
</style>