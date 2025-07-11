import { describe, test, expect, beforeEach, vi } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import RecipeSearch from '../../components/RecipeSearch.vue'
import { useSearchStore } from '../../stores/search.js'

// Mock the RecipeCard component
const RecipeCard = {
  name: 'RecipeCard',
  props: ['recipe'],
  template: '<div class="recipe-card">{{ recipe.name }}</div>'
}

// Mock $fetch
global.$fetch = vi.fn()

describe('RecipeSearch', () => {
  let pinia
  let searchStore

  beforeEach(() => {
    // Create a fresh Pinia instance for each test
    pinia = createPinia()
    setActivePinia(pinia)
    searchStore = useSearchStore()
    
    // Reset mocks
    vi.resetAllMocks()
    
    // Default mock implementation
    global.$fetch.mockResolvedValue({
      data: [],
      meta: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0
      }
    })
  })

  test('renders the search form correctly', () => {
    const wrapper = mount(RecipeSearch, {
      global: {
        plugins: [pinia],
        components: {
          RecipeCard
        }
      }
    })

    expect(wrapper.find('h2').text()).toBe('Search Recipes')
    expect(wrapper.find('label[for="keyword"]').exists()).toBe(true)
    expect(wrapper.find('label[for="ingredient"]').exists()).toBe(true)
    expect(wrapper.find('label[for="author_email"]').exists()).toBe(true)
    expect(wrapper.find('button[type="submit"]').exists()).toBe(true)
    expect(wrapper.find('button[type="button"]').text()).toBe('Reset')
  })

  test('updates store when inputs change', async () => {
    const wrapper = mount(RecipeSearch, {
      global: {
        plugins: [pinia],
        components: {
          RecipeCard
        }
      }
    })

    // Find the input elements
    const keywordInput = wrapper.find('#keyword')
    const ingredientInput = wrapper.find('#ingredient')
    const authorEmailInput = wrapper.find('#author_email')

    // Set values
    await keywordInput.setValue('cake')
    await ingredientInput.setValue('flour')
    await authorEmailInput.setValue('test@example.com')

    // Check that the store was updated
    expect(searchStore.keyword).toBe('cake')
    expect(searchStore.ingredient).toBe('flour')
    expect(searchStore.authorEmail).toBe('test@example.com')
  })
    
  test('displays no recipes message when no results', async () => {
    const wrapper = mount(RecipeSearch, {
      global: {
        plugins: [pinia],
        components: {
          RecipeCard
        }
      }
    })

    // Wait for the initial API call to resolve
    await flushPromises()

    // Check that no recipes message is displayed
    expect(wrapper.text()).toContain('No recipes found. Try different search criteria.')
  })
})
