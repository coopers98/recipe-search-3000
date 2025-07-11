import { describe, test, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import RecipeCard from '../../components/RecipeCard.vue'

// Mock navigateTo function
const mockNavigateTo = vi.fn()
vi.stubGlobal('navigateTo', mockNavigateTo)

describe('RecipeCard', () => {
  const mockRecipe = {
    id: 1,
    name: 'Test Recipe',
    description: 'This is a test recipe description',
    ingredients: [
      { id: 1, name: 'Ingredient 1', quantity: '1', unit: 'cup' },
      { id: 2, name: 'Ingredient 2', quantity: '2', unit: 'tbsp' },
      { id: 3, name: 'Ingredient 3', quantity: '3', unit: 'tsp' },
      { id: 4, name: 'Ingredient 4', quantity: '4', unit: 'oz' }
    ],
    steps: [
      { step_number: 1, description: 'Step 1', duration_minutes: 5 },
      { step_number: 2, description: 'Step 2', duration_minutes: 10 }
    ],
    author_email: 'test@example.com',
    slug: 'test-recipe'
  }

  test('renders recipe name correctly', () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    expect(wrapper.find('h3').text()).toBe('Test Recipe')
  })

  test('renders recipe description correctly', () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    expect(wrapper.find('p.text-gray-600.mb-4').text()).toBe('This is a test recipe description')
  })

  test('formats ingredients correctly', () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    // Should show first 3 ingredients with ellipsis
    expect(wrapper.find('p.text-gray-600.text-sm').text()).toContain('Ingredient 1, Ingredient 2, Ingredient 3, ...')
  })

  test('displays author email correctly', () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    expect(wrapper.find('.text-gray-500 span').text()).toBe('test@example.com')
  })

  test('navigates to recipe page when card is clicked', async () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    await wrapper.trigger('click')
    
    expect(mockNavigateTo).toHaveBeenCalledWith('/recipes/test-recipe')
  })

  test('navigates to recipe page when view recipe button is clicked', async () => {
    const wrapper = mount(RecipeCard, {
      props: {
        recipe: mockRecipe
      }
    })
    
    await wrapper.find('button').trigger('click')
    
    expect(mockNavigateTo).toHaveBeenCalledWith('/recipes/test-recipe')
  })
})