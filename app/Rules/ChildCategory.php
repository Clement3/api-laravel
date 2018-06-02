<?php

namespace App\Rules;

use App\Category;
use Illuminate\Contracts\Validation\Rule;

class ChildCategory implements Rule
{
    protected $parent_category;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parent_category)
    {
        $this->parent_category = $parent_category;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $category = Category::find($value);

        if ($category) {
            return $category->parent_id === $this->parent_category;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.is_a_child_of_the_parent');
    }
}
