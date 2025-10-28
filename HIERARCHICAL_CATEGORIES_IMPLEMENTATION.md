# Hierarchical Categories Implementation Guide

## Overview
This document explains the hierarchical categories feature that allows creating parent-child relationships between categories (e.g., "Appetizers" → "Cold Appetizers", "Hot Appetizers").

## Database Changes Required

### Step 1: Run SQL Migration

You need to add the `parent_id` field to the `tbl_food_menu_categories` table:

```sql
-- Add parent_id column to tbl_food_menu_categories table
ALTER TABLE `tbl_food_menu_categories` 
ADD COLUMN `parent_id` INT(11) DEFAULT 0 AFTER `id`,
ADD INDEX `idx_parent_id` (`parent_id`);

-- Add a comment for clarity
ALTER TABLE `tbl_food_menu_categories` 
MODIFY COLUMN `parent_id` INT(11) DEFAULT 0 COMMENT 'Parent category ID for hierarchical structure. 0 or NULL means top-level category';
```

**Important:** Existing categories will automatically have `parent_id = 0` (top-level).

---

## What Was Implemented

### 1. **Database Layer** (Common_model.php)

Added new methods:
- `getAllCategoriesWithHierarchy($company_id, $parent_id = 0)` - Get categories by parent
- `getChildCategories($parent_id)` - Get direct children of a category
- `getCategoriesForDropdown($company_id, $exclude_id, $parent_id)` - Get categories for dropdown (with exclusion)
- `getCategoryPath($category_id)` - Get breadcrumb path
- `canBeParent($current_id, $proposed_parent_id)` - Check if circular reference would occur
- `getAllDescendants($parent_id)` - Get all descendants recursively
- `checkCategoryHasItems($category_id)` - Check if category has food items

### 2. **Controller** (FoodMenuCategory.php)

Changes:
- Modified `addEditFoodMenuCategory()` to handle `parent_id` in POST data
- Added circular reference validation
- Updated `foodMenuCategories()` to display hierarchical structure
- Added `buildCategoryHierarchy()` method to recursively build hierarchy
- Enhanced `deleteFoodMenuCategory()` to prevent deletion of categories with children or items

### 3. **Views**

#### addFoodMenuCategory.php
- Added parent category dropdown
- Shows "Top Level Category" as default option

#### editFoodMenuCategory.php
- Added parent category dropdown
- Pre-selects current parent category
- Excludes current category from dropdown to prevent self-reference

#### foodMenuCategories.php
- Displays hierarchy with indentation (├─ prefix)
- Shows level-based indentation

### 4. **Helper Functions** (my_helper.php)

Updated:
- `getFoodMenuCategory($parent_id = 0)` - Now accepts parent_id parameter to filter by hierarchy level

---

## How to Use

### Creating Top-Level Categories
1. Go to **Food Menu Categories**
2. Click **Add Category**
3. Enter category name, image, description
4. Leave **Parent Category** as "Top Level Category"
5. Click **Submit**

### Creating Subcategories
1. Go to **Food Menu Categories**
2. Click **Add Category**
3. Enter subcategory name, image, description
4. Select a **Parent Category** from the dropdown
5. Click **Submit**

### Viewing Hierarchy
Categories are displayed in a hierarchical structure with indentation:
```
Sn  Category Name              Image    Description  Added By  Actions
1   Appetizers                 [img]    [desc]      [user]    [edit] [delete]
     ├─ Cold Appetizers        [img]    [desc]      [user]    [edit] [delete]
     ├─ Hot Appetizers         [img]    [desc]      [user]    [edit] [delete]
2   Main Courses               [img]    [desc]      [user]    [edit] [delete]
     ├─ Chicken Dishes         [img]    [desc]      [user]    [edit] [delete]
     ├─ Beef Dishes            [img]    [desc]      [user]    [edit] [delete]
```

### Editing Categories
- You can change the parent category at any time
- System prevents circular references automatically
- If trying to set a descendant as parent, you'll get an error

### Deleting Categories
- **Cannot delete** categories that have subcategories
- **Cannot delete** categories that have food items assigned
- Delete subcategories first, then parent categories

---

## Safety Features

### 1. Circular Reference Prevention
- Prevents setting a category as its own parent
- Prevents setting a descendant as a parent (would create cycles)
- Example: If "Cold Appetizers" is a child of "Appetizers", you cannot set "Appetizers" as a child of "Cold Appetizers"

### 2. Deletion Protection
- Cannot delete categories with children
- Cannot delete categories with food items
- Forces cleanup in correct order (children first)

### 3. Data Integrity
- All operations maintain referential integrity
- Parent-child relationships are validated before saving
- Historical data remains intact

---

## Frontend Integration

The frontend menu page (menu-page.php) will now automatically show only top-level categories in the category sidebar. You can update it to show subcategories as needed:

```php
// Get top-level categories only
$categories = getFoodMenuCategory(0);

// Or get subcategories of a specific parent
$cold_appetizers = getFoodMenuCategory($appetizer_id);
```

---

## API Integration

For API endpoints, you can query categories by parent:

```php
// Get top-level categories
$model->getAllCategoriesWithHierarchy($company_id, 0);

// Get subcategories
$model->getChildCategories($parent_id);
```

---

## Testing Checklist

- [ ] Run SQL migration to add `parent_id` column
- [ ] Test creating top-level categories
- [ ] Test creating subcategories
- [ ] Test editing category parent
- [ ] Test circular reference prevention
- [ ] Test deleting categories (should fail if has children/items)
- [ ] Test category listing hierarchy display
- [ ] Test frontend category display

---

## Notes

- Maximum depth: No hard limit, but UI may need adjustment for deep hierarchies
- Performance: Queries are indexed and optimized
- Compatibility: Existing categories work without changes (parent_id = 0)
- Migration: Non-destructive - can be rolled back by removing parent_id column

---

## Support

For issues or questions about hierarchical categories:
1. Check that migration SQL has been run
2. Verify parent_id column exists in database
3. Check browser console for JavaScript errors
4. Verify user has proper permissions


