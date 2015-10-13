<?php
// src/ChopShopper/Entity/RecipeStep.php
namespace ChopShopper\Entity;

/**
 * Ingredient
 * Ingredient used in recipe.
 * @Entity(repositoryClass="ChopShopper\Entity\IngredientRepository")
 */
 class Ingredient {

   /**
    * @var integer
    *
    * @Column(name="id", type="integer")
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
     * @var string
     *
     * @Column(length=32, unique=true)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Ingredient
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
