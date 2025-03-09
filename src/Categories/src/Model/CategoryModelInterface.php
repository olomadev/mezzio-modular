<?php

declare(strict_types=1);

namespace Categories\Model;

interface CategoryModelInterface
{
    /**
     * Get all categories
     * 
     * @return array
     */
    public function findAll(): array;

    /**
     * Get all categories in nested format
     * @return array
     */
    public function findAllNested(): array;

    /**
     * Create a new category
     * 
     * @param array $data
     * @return void
     */
    public function create(array $data);

    /**
     * Delete a category by category id
     * 
     * @param string $categoryId
     * @return void
     */
    public function delete(string $categoryId);

    /**
     * Update an existing category
     * 
     * @param array $data
     * @param bool $move move category to under another node
     * @return void
     */
    public function update(array $data, bool $move = false);
}