<template>
    <div>
        <!-- Search Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Search Recipes</h2>
            <form @submit.prevent="handleSearch" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                        <input
                            id="keyword"
                            v-model="searchStore.keyword"
                            type="text"
                            placeholder="Search in name, description, ingredients, or steps"
                            class="w-full p-2 border border-gray-300 rounded"
                        />
                    </div>
                    <div>
                        <label for="ingredient" class="block text-sm font-medium text-gray-700 mb-1">Ingredient</label>
                        <input
                            id="ingredient"
                            v-model="searchStore.ingredient"
                            type="text"
                            placeholder="Search for a specific ingredient"
                            class="w-full p-2 border border-gray-300 rounded"
                        />
                    </div>
                    <div>
                        <label for="author_email" class="block text-sm font-medium text-gray-700 mb-1">Author
                            Email</label>
                        <input
                            id="author_email"
                            v-model="searchStore.authorEmail"
                            type="email"
                            placeholder="Search by author's email"
                            class="w-full p-2 border border-gray-300 rounded"
                        />
                    </div>
                </div>
                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="handleReset"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition"
                        :disabled="!searchStore.hasSearchCriteria"
                        :class="{ 'opacity-50 cursor-not-allowed': !searchStore.hasSearchCriteria }"
                    >
                        Reset
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                        :disabled="searchStore.isLoading"
                    >
                        <span v-if="searchStore.isLoading">Searching...</span>
                        <span v-else>Search</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Error State -->
        <div v-if="searchStore.error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ searchStore.error }}</p>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="searchStore.isLoading" class="text-center py-8">
            <div
                class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-2"></div>
            <p class="text-lg">Loading recipes...</p>
        </div>

        <!-- Results -->
        <div v-else>
            <div v-if="searchStore.recipes.length === 0 && !searchStore.isLoading" class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-lg">No recipes found. Try different search criteria.</p>
            </div>
            <div v-else-if="searchStore.recipes.length > 0">
                <h2 class="text-xl font-semibold mb-4">{{ searchStore.totalRecipes }} Recipes Found</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <RecipeCard
                        v-for="recipe in searchStore.recipes"
                        :key="recipe.id"
                        :recipe="recipe"
                        class="cursor-pointer hover:shadow-lg transition-shadow duration-200"
                    />
                </div>

                <!-- Pagination -->
                <div v-if="searchStore.totalPages > 1" class="mt-8 flex justify-center">
                    <div class="flex flex-col items-center">
                        <!-- Pagination Controls -->
                        <div class="flex space-x-2">
                            <!-- Previous Page Button -->
                            <button
                                @click="goToPage(searchStore.currentPage - 1)"
                                :disabled="searchStore.currentPage === 1"
                                class="px-3 py-1 rounded border"
                                :class="searchStore.currentPage === 1
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200'
                                    : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                            >
                                &laquo; Prev
                            </button>

                            <!-- Page Numbers -->
                            <template v-if="searchStore.totalPages <= 7">
                                <button
                                    v-for="page in searchStore.totalPages"
                                    :key="page"
                                    @click="goToPage(page)"
                                    class="px-3 py-1 rounded border"
                                    :class="searchStore.currentPage === page
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                                >
                                    {{ page }}
                                </button>
                            </template>

                            <template v-else>
                                <!-- First page -->
                                <button
                                    @click="goToPage(1)"
                                    class="px-3 py-1 rounded border"
                                    :class="searchStore.currentPage === 1
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                                >
                                    1
                                </button>

                                <!-- Ellipsis if needed -->
                                <span v-if="searchStore.currentPage > 3" class="px-2 py-1">...</span>

                                <!-- Pages around current page -->
                                <template v-for="page in searchStore.totalPages" :key="page">
                                    <button
                                        v-if="page !== 1 && page !== searchStore.totalPages && Math.abs(page - searchStore.currentPage) <= 1"
                                        @click="goToPage(page)"
                                        class="px-3 py-1 rounded border"
                                        :class="searchStore.currentPage === page
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                                    >
                                        {{ page }}
                                    </button>
                                </template>

                                <!-- Ellipsis if needed -->
                                <span v-if="searchStore.currentPage < searchStore.totalPages - 2" class="px-2 py-1">...</span>

                                <!-- Last page -->
                                <button
                                    @click="goToPage(searchStore.totalPages)"
                                    class="px-3 py-1 rounded border"
                                    :class="searchStore.currentPage === searchStore.totalPages
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                                >
                                    {{ searchStore.totalPages }}
                                </button>
                            </template>

                            <!-- Next Page Button -->
                            <button
                                @click="goToPage(searchStore.currentPage + 1)"
                                :disabled="searchStore.currentPage === searchStore.totalPages"
                                class="px-3 py-1 rounded border"
                                :class="searchStore.currentPage === searchStore.totalPages
                                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200'
                                    : 'bg-white text-blue-600 hover:bg-blue-50 border-blue-300'"
                            >
                                Next &raquo;
                            </button>
                        </div>

                        <!-- Pagination Info -->
                        <div class="mt-3 text-sm text-gray-600">
                            Showing page {{ searchStore.currentPage }} of {{ searchStore.totalPages }} ({{ searchStore.totalRecipes }} total recipes)
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useSearchStore } from '~/stores/search'

// Use the search store
const searchStore = useSearchStore()

// Methods
const handleSearch = async () => {
  searchStore.setCurrentPage(1)
  await searchStore.performSearch()
}

const handleReset = async () => {
  searchStore.resetSearch()
  await searchStore.performSearch()
}

const goToPage = async (page) => {
  if (page < 1 || page > searchStore.totalPages || page === searchStore.currentPage) {
    return
  }
  
  searchStore.setCurrentPage(page)
  await searchStore.performSearch()
}

// Load initial data
onMounted(async () => {
  await searchStore.performSearch()
})
</script>

<style scoped>
/* Component-specific styles */
</style>
