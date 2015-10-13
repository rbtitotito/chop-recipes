CREATE TABLE ingredient (id INTEGER NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_6BAF78705E237E06 ON ingredient (name);
CREATE TABLE recipe (id INTEGER NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_DA88B1375E237E06 ON recipe (name);
CREATE TABLE recipe_step (id INTEGER NOT NULL, recipe_id INTEGER UNSIGNED NOT NULL, directions VARCHAR(400) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_3CA2A4E359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
CREATE INDEX IDX_3CA2A4E359D8A214 ON recipe_step (recipe_id);
CREATE TABLE recipe_step_ingredients (recipe_step_id INTEGER UNSIGNED NOT NULL, ingredient_id INTEGER UNSIGNED NOT NULL, qty NUMERIC(10, 2) NOT NULL, PRIMARY KEY(recipe_step_id, ingredient_id), CONSTRAINT FK_EDA52493F5610DC FOREIGN KEY (recipe_step_id) REFERENCES recipe_step (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EDA5249933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
CREATE INDEX IDX_EDA52493F5610DC ON recipe_step_ingredients (recipe_step_id);
CREATE INDEX IDX_EDA5249933FE08C ON recipe_step_ingredients (ingredient_id);
