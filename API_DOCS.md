# API Documentation

## Authentication
All endpoints require authentication using Sanctum tokens.

## Categories Endpoints

### GET /api/categories
Get all categories for authenticated user
- **Response**: Array of categories with plats count

### POST /api/categories
Create a new category
- **Body**: `{ "name": "string", "description": "string" }`

### GET /api/categories/{id}
Get specific category with plats

### PUT /api/categories/{id}
Update category

### DELETE /api/categories/{id}
Delete category

### GET /api/categories/{id}/plats
Get all plats in a category

### GET /api/categories/{id}/stats
Get category statistics

## Plats Endpoints

### GET /api/plats
Get all plats for authenticated user
- **Query params**: 
  - `search`: Filter by name
  - `category_id`: Filter by category

### POST /api/plats
Create a new plat
- **Body**: `{ "name": "string", "description": "string", "price": "number", "category_id": "number" }`

### GET /api/plats/{id}
Get specific plat

### PUT /api/plats/{id}
Update plat

### DELETE /api/plats/{id}
Delete plat