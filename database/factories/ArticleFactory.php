<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = $this->getUsers();
        $categories = $this->getCategories();

        return [
            'title' => $this->faker->words(8, true),
            'content' => $this->faker->sentence(45),
            'category_id' => $categories->random()->id,
            'author_id' => $users->random()->id,
            'tags' => $this->faker->words(4),
            'published_at' => $this->faker->dateTime(),
        ];
    }

    /**
     * 获取用户列表
     *
     * @return Collection
     */
    private function getUsers(): Collection
    {
        if (User::all()->count() < 0) {
            User::factory(5)->create();
        }

        return User::all();
    }

    /**
     * 获取文章分类列表
     *
     * @return Collection
     */
    private function getCategories(): Collection
    {
        if (Category::all()->count() < 0) {
            Category::factory(5)->create();
        }

        return Category::all();
    }
}
