<template>
  <div
    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer"
    @click="navigateToRecipe"
  >
    <div class="p-5">
      <h3 class="text-xl font-semibold mb-2 truncate">{{ recipe.name }}</h3>
      <p class="text-gray-600 mb-4 line-clamp-2">{{ recipe.description }}</p>

      <div class="mb-3">
        <h4 class="text-sm font-medium text-gray-700 mb-1">Ingredients:</h4>
        <p class="text-gray-600 text-sm line-clamp-2">
          {{ formatIngredients(recipe.ingredients) }}
        </p>
      </div>

      <div class="text-sm text-gray-500 mt-4 flex justify-between items-center">
        <span>{{ recipe.author_email }}</span>
        <button
          class="text-blue-600 hover:text-blue-800"
          @click.stop="navigateToRecipe"
        >
          View Recipe
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  recipe: {
    type: Object,
    required: true
  }
});

/**
 * Navigate to recipe detail page
 */
const navigateToRecipe = () => {
  navigateTo(`/recipes/${props.recipe.slug}`)
}

/**
 * Format ingredients list for display
 */
function formatIngredients(ingredients) {
  if (!ingredients || !ingredients.length) return '';

  // Handle ingredient objects
  const ingredientNames = ingredients.map(ingredient => {
    if (typeof ingredient === 'object') {
      return ingredient.name;
    }
    return ingredient;
  });

  // Show first 3 ingredients with ellipsis if more
  const displayIngredients = ingredientNames.slice(0, 3).join(', ');
  return ingredients.length > 3 ? `${displayIngredients}, ...` : displayIngredients;
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
