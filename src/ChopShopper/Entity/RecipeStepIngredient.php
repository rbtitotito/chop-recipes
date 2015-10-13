<?php
// src/ChopShopper/Entity/RecipeStep.php
namespace ChopShopper\Entity;

/**
 * RecipeStep links together Recipes and Ingredients
 * with a direction.
 * This way, the ingredient list for the entire recipe
 * can be built while the directions are being built.
 * @Entity(repositoryClass="ChopShopper\Entity\RecipeStepIngredientRepository")
 * @Table(name="recipe_step_ingredients")
 */
 class RecipeStepIngredient {

   /**
    * @Id @Column(type="integer")
    * @GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @var Ingredient
     *
     * @OneToOne(targetEntity="Ingredient")
     */
    private $ingredient;

    /**
     * @var RecipeStep
     * @ManyToOne(targetEntity="RecipeStep", inversedBy="step_ingredients")
     * @JoinColumn(name="recipe_step_id", referencedColumnName="id")
     */
    private $recipeStep;

    /**
     * @var float
     * Quantity of Ingredient to use
     * @Column(type="decimal", scale=2, precision=10)
     **/
    private $qty;


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
     * Set qty
     *
     * @param string $qty
     *
     * @return RecipeStepIngredient
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return string
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set ingredient
     *
     * @param \ChopShopper\Entity\Ingredient $ingredient
     *
     * @return RecipeStepIngredient
     */
    public function setIngredient(\ChopShopper\Entity\Ingredient $ingredient = null)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return \ChopShopper\Entity\Ingredient
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set recipeStep
     *
     * @param \ChopShopper\Entity\RecipeStep $recipeStep
     *
     * @return RecipeStepIngredient
     */
    public function setRecipeStep(\ChopShopper\Entity\RecipeStep $recipeStep = null)
    {
        $this->recipeStep = $recipeStep;

        return $this;
    }

    /**
     * Get recipeStep
     *
     * @return \ChopShopper\Entity\RecipeStep
     */
    public function getRecipeStep()
    {
        return $this->recipeStep;
    }
}
