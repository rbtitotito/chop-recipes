<?php

/**
 * @author Rob Brown <rbtitotito@gmail.com>
 */

$schema = new \Doctrine\DBAL\Schema\Schema();

$ingredient = $schema->createTable('ingredient');
$ingredient->addColumn('id', 'integer', array('autoincrement' => true));
$ingredient->addColumn('name', 'string', array('length' => 32));
$ingredient->setPrimaryKey(array('id'));
$ingredient->addUniqueIndex(array('name'));

$recipe = $schema->createTable('recipe');
$recipe->addColumn('id', 'integer', array('autoincrement' => true));
$recipe->addColumn('name', 'string', array('length' => 32));
$recipe->setPrimaryKey(array('id'));
$recipe->addUniqueIndex(array('name'));

$step = $schema->createTable('recipe_step');
$step->addColumn('id', 'integer', array('autoincrement' => true));
$step->addColumn('recipe_id', 'integer', array('unsigned' => true));
$step->addColumn('directions', 'string', array('length' => 400));
$step->setPrimaryKey(array('id'));
$step->addForeignKeyConstraint($recipe, array('recipe_id'), array('id'));

$step_item = $schema->createTable('recipe_step_ingredients');
$step_item->addColumn('recipe_step_id', 'integer', array('unsigned' => true));
$step_item->addColumn('ingredient_id', 'integer', array('unsigned' => true));
$step_item->addColumn('qty', 'decimal', array('precision'=>10,'scale'=>2));
$step_item->setPrimaryKey(array('recipe_step_id', 'ingredient_id'));
$step_item->addForeignKeyConstraint($step, array('recipe_step_id'), array('id'));
$step_item->addForeignKeyConstraint($ingredient, array('ingredient_id'), array('id'));

return $schema;
