-- There is a CHECK constraint that prevents a rating of a movie from being below 0 or above 5
INSERT INTO Review VALUES ('Brett the Critic', NULL, 2, 6, 'This movie is sooooo gooooooooood, mostly cause it exists');

-- There is a foreign key constraint on Review that requires its `mid` to exist in Movie as id
-- ERROR 1452 (23000) at line 2: Cannot add or update a child row: a foreign key constraint fails
--   (`CS143`.`Review`, CONSTRAINT `Review_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
INSERT INTO Review VALUES ('Brett the Critic', NULL, 1, 0, 'This movie sucksssssssss, mostly cause it doesn\'t exist');

-- There is a foreign key constraint from MovieActor that requires its `mid` to exist in Movie as id
-- ERROR 1452 (23000) at line 2: Cannot add or update a child row: a foreign key constraint fails
--  (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
INSERT INTO MovieActor VALUES (1, 1, 'Ice Cream');

-- There is a foreign key constraint from MovieDirector that requires its `did` to exist in Director as id
-- ERROR 1452 (23000) at line 4: Cannot add or update a child row: a foreign key constraint fails
--   (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_2` FOREIGN KEY (`did`) REFERENCES `Director` (`id`))
INSERT INTO MovieDirector VALUES (2, 15);

-- There is a foreign key constraint from MovieDirector that requires its mid to exist in Movie as id
-- ERROR 1452 (23000) at line 1: Cannot add or update a child row: a foreign key constraint fails
--   (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
INSERT INTO MovieDirector VALUES (1, 16);

-- There is a foreign key constraint from MovieGenre that requires its mid to exist in Movie as id
-- ERROR 1451 (23000) at line 1: Cannot delete or update a parent row: a foreign key constraint fails
--   (`CS143`.`MovieGenre`, CONSTRAINT `MovieGenre_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))
DELETE FROM Movie WHERE id = 2;

-- There is a primary key constraint and there is already a MovieGenre row with mid = 2
-- ERROR 1062 (23000) at line 2: Duplicate entry '2' for key 'PRIMARY'
INSERT INTO MovieGenre VALUES (2, 'Comedy');

-- There is a CHECK constraint that prevents a Director from being added if an actor has the same id but different
--   information
INSERT INTO Director VALUES (1, 'B', 'Bob', '1960-07-15', NULL);

-- There is a CHECK constraint that prevents a Director from being added if the date of death comes before the date of
--   birth
UPDATE Director SET dod = '1960-07-15' WHERE dob > '1960-07-15';

-- There is a primary key constraint on the Director id and a Director already exists with the id = 63
-- ERROR 1062 (23000) at line 2: Duplicate entry '63' for key 'PRIMARY'
INSERT INTO Director VALUES (63, 'Aman', 'Agarwal', '1960-07-15', NULL);

-- There is a CHECK constraint that prevents an Actor from being added if the date of death comes before the date of
--   birth
INSERT INTO Actor VALUES (2, 'B', 'Bob', 'Male', '1966-09-17', '1948-01-10');

-- There is a primary key constraint on the Actor id and an Actor already exists with the id = 1
-- ERROR 1062 (23000) at line 3: Duplicate entry '1' for key 'PRIMARY'
INSERT INTO Actor VALUES (1, 'B', 'Bob', 'Male', '1966-09-17', NULL);

-- There is a primary key constraint on the Movie id and a Movie already exists with the id = 2
-- ERROR 1062 (23000) at line 4: Duplicate entry '2' for key 'PRIMARY'
INSERT INTO Movie VALUES (2, 'Till There You Was', 1999, 'R', 'Turnip King Entertainment (TKE)');

-- There is no actor with id = 2 and MovieActor has a foreign key constraint requiring aid to be in Actor as id
-- Cannot add or update a child row: a foreign key constraint fails
--   (`TEST`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `Actor` (`id`))
INSERT INTO MovieActor VALUES (2, 2, 'Turnip King');


