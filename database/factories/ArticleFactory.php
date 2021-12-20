<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $long_text =  $this->faker->paragraph($nbSentences = 10, $variableNbSentences = true);
        $short_text =  mb_strimwidth($long_text, 0, 20, ' (...)');
        $category = $this->faker->randomElement($array = array (3, 5, 6));

        switch ($category) {
            case 3:
                $subcategory = $this->faker->randomElement($array = array (1, 2));
                break;
            case 5:
                $subcategory = $this->faker->randomElement($array = array (3, 6));
                break;
            case 6:
                $subcategory = $this->faker->randomElement($array = array (4, 5));
                break;
            default:
                $subcategory = $this->faker->randomElement($array = array (1, 2));
        }

    	return [
            'category_id' => $category,
            'subcategory_id' => $subcategory,
            'title' => $this->faker->sentence($nbWords = 5, $variableNbWords = true),
            'long_text' => $long_text,
            'short_text' => $short_text,
            'image_url' => $this->faker->imageUrl($width = 640, $height = 480),
            'publish_date' => $this->faker->dateTimeThisYear($max = 'now', $timezone = null)
        ];
    }
}
