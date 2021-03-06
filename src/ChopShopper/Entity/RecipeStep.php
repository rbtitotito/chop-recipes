<?php
// src/ChopShopper/Entity/RecipeStep.php
namespace ChopShopper\Entity;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * RecipeStep links together Recipes and Ingredients
 * with a direction.
 * This way, the ingredient list for the entire recipe
 * can be built while the directions are being built.
 * @Entity(repositoryClass="ChopShopper\Entity\RecipeStepRepository")
 * @Table(name="recipe_step")
 */
class RecipeStep
{

   /**
    * @var id
    *
    * @Id @Column(type="integer")
    * @GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @var string
     *
     * The text direction for this particular step.
     * Should include all direction necessary to use attached ingredients.
     * @Column(length=400)
     */
    private $directions;

    /**
     * @var \ChopShopper\Entity\Recipe
     *
     * @ManyToOne(targetEntity="Recipe", inversedBy="steps")
     * @JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @OneToMany(targetEntity="RecipeStepIngredient", mappedBy="recipeStep")
     **/
    private $step_ingredients;

    public function __construct($template = null)
    {
        $this->step_ingredients = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set directions
     *
     * @param string $directions
     *
     * @return RecipeStep
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions
     *
     * @return string
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Set recipe
     *
     * @param \ChopShopper\Entity\Recipe $recipe
     *
     * @return RecipeStep
     */
    public function setRecipe(\ChopShopper\Entity\Recipe $recipe = null)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return \ChopShopper\Entity\Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * Add stepIngredient
     *
     * @param \ChopShopper\Entity\RecipeStepIngredient $stepIngredient
     *
     * @return RecipeStep
     */
    public function addStepIngredient(\ChopShopper\Entity\RecipeStepIngredient $stepIngredient)
    {
        $this->step_ingredients[] = $stepIngredient;

        return $this;
    }

    /**
     * Remove stepIngredient
     *
     * @param \ChopShopper\Entity\RecipeStepIngredient $stepIngredient
     */
    public function removeStepIngredient(\ChopShopper\Entity\RecipeStepIngredient $stepIngredient)
    {
        $this->step_ingredients->removeElement($stepIngredient);
    }

    /**
     * Get stepIngredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStepIngredients()
    {
        return $this->step_ingredients;
    }

    public function toArray()
    {
        $ret = array();
        $ret['directions'] = $this->directions;

        foreach ($this->getStepIngredients() as $step_ingredient) {
            if (!isset($ret['step_ingredients'])) {
                $ret['step_ingredients'] = array();
            }

            $ret['step_ingredients'] = $step_ingredient->toArray();
        }

        return $ret;
    }
}
