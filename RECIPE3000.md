# Recipe Search 3000

## Introduction

WAC introduces Recipe Search 3000, a web-based application that enables users to search recipes by keywords and ingredients.

## 1. Scope

The search feature will allow users to search a database of recipes by:

- Ingredients
- Keywords
- Author’s email address

## 2. Requirements

### 2.1 Create the recipe

Design and create tables necessary to store recipes with:

- **Name**
- **Description**
- **List of ingredients** (unordered list)
- **List of steps** (ordered list of text)
- **Author email**
- **Unique slug** (auto-generated based on the name)

### 2.2 Searching for recipes

Create a search UI with the following requirements:

- Allow searching for recipes by:
    - **Author email** – exact match
    - **Keyword** – matches ANY of these fields: name, description, ingredients, or steps
    - **Ingredient** – partial match (e.g. “potato” matches “3 large potatoes”)

- Allow combinations of search parameters, queried as **AND conditions**.

### Getting Started

Use the pre-made **WAC Laravel Skeleton Application**:

- [Laravel + Nuxt Skeleton](https://github.com/wildalaskan/skeleton-app)
- [Laravel + Vue Skeleton](https://github.com/wildalaskan/skeleton-app-vue)

**Notes:**

- Uses Laravel Sail with Docker (no local PHP or Node required)
- Nuxt frontend can be hosted standalone
- User authentication is **not required**
- No UI is needed to create or edit recipes; provide a **command, seeder, or reusable method** to create them in the database

Please read and understand this entire document before starting.

### 2.3 Viewing recipes

- Display a **paginated list of recipes** from the database
- If a search is performed, only display matching recipes
- Pagination should **retain search parameters** when switch
