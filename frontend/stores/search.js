import { defineStore } from 'pinia'

export const useSearchStore = defineStore('search', {
  state: () => ({
    // Search criteria
    keyword: '',
    ingredient: '',
    authorEmail: '',
    
    // Search results and pagination
    recipes: [],
    currentPage: 1,
    totalPages: 1,
    totalRecipes: 0,
    isLoading: false,
    error: null
  }),

  getters: {
    hasSearchCriteria: (state) => {
      return !!(state.keyword || state.ingredient || state.authorEmail)
    },
    
    searchParams: (state) => {
      const params = {}
      if (state.keyword) params.keyword = state.keyword
      if (state.ingredient) params.ingredient = state.ingredient
      if (state.authorEmail) params.author_email = state.authorEmail
      if (state.currentPage > 1) params.page = state.currentPage
      return params
    }
  },

  actions: {
    // Update search criteria
    setKeyword(keyword) {
      this.keyword = keyword
    },
    
    setIngredient(ingredient) {
      this.ingredient = ingredient
    },
    
    setAuthorEmail(authorEmail) {
      this.authorEmail = authorEmail
    },
    
    // Reset all search criteria
    resetSearch() {
      this.keyword = ''
      this.ingredient = ''
      this.authorEmail = ''
      this.currentPage = 1
      this.recipes = []
      this.totalPages = 1
      this.totalRecipes = 0
      this.error = null
    },
    
    // Set current page
    setCurrentPage(page) {
      this.currentPage = page
    },
    
    // Update search results
    setSearchResults(data) {
      this.recipes = data.data || []
      this.currentPage = data.meta?.current_page || 1
      this.totalPages = data.meta?.last_page || 1
      this.totalRecipes = data.meta?.total || 0
    },
    
    // Set loading state
    setLoading(loading) {
      this.isLoading = loading
    },
    
    // Set error state
    setError(error) {
      this.error = error
    },
    
    // Perform search
    async performSearch() {
      this.setLoading(true)
      this.setError(null)
      
      try {
        const params = new URLSearchParams(this.searchParams)
        const API_URL = 'http://localhost:8888/api/recipes'
        
        const response = await $fetch(`${API_URL}?${params.toString()}`)
        this.setSearchResults(response)
      } catch (error) {
        console.error('Search failed:', error)
        this.setError('Failed to search recipes. Please try again.')
      } finally {
        this.setLoading(false)
      }
    }
  }
})