<?php
// src/ChopShopper/Entity/Recipe.php
namespace ChopShopper\Entity;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * A Recipe consists of:
 *
 * * Name (max: 400)
 * * At least 1 Recipe step
 * * * With At least 1 recipe
 *
 * @Entity(repositoryClass="ChopShopper\Entity\RecipeRepository")
 */
 class Recipe {

   /**
    * @var integer
    *
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id;

    /**
     * @var string
     *
     * @Column(length=32)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * Array of RecipeStep Objects
     *
     * @OneToMany(targetEntity="RecipeStep", mappedBy="recipe")
     **/
    private $steps;

    /**
     * @var boolean
     *
     * deleted flag @transient
     */
    private $deleted;

    public function __construct()
    {
      $this->steps = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @return Recipe
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return Recipe
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

    /**
     * Add step
     *
     * @param \ChopShopper\Entity\RecipeStep $step
     *
     * @return Recipe
     */
    public function addStep(\ChopShopper\Entity\RecipeStep $step)
    {
        $this->steps[] = $step;

        return $this;
    }

    /**
     * Remove step
     *
     * @param \ChopShopper\Entity\RecipeStep $step
     */
    public function removeStep(\ChopShopper\Entity\RecipeStep $step)
    {
        $this->steps->removeElement($step);
    }

    /**
     * Get steps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Recipe
     */
    public function setDeleted($deleted)
    {
        if ($deleted === false || $deleted === true) {
            $this->deleted = $deleted;
        }

        return $this;
    }


    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Return array representation
     *
     * @return array
     */
    public function toArray()
    {
        $ret = array();
        $ret['recipe_id'] = $this->id;
        $ret['name'] = $this->name;

        if ($this->deleted) {
            $ret['deleted'] = $this->deleted;
        }
        
        return $ret;
    }
}
