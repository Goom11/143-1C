-- Get all the first and last names of actors who acted in the movie titled 'Die Another Day'
SELECT CONCAT(first, ' ', last)
FROM Movie M, Actor A, MovieActor MA
WHERE M.id = MA.mid AND A.id = MA.aid AND M.title = 'Die Another Day';

-- Get the number of actors/actresses who acted in more than one movie
SELECT COUNT(*)
FROM Actor A
WHERE A.id IN (
  SELECT MA.aid
  FROM MovieActor MA
  WHERE A.id = MA.aid
  GROUP BY MA.aid
  HAVING COUNT(*) > 1
);

-- Get the titles of 10 movies with the most actors in descending order and the number of actors in those movies
SELECT M.title, COUNT(*)
FROM MovieActor MA, Movie M
WHERE MA.mid = M.id
GROUP BY MA.mid
ORDER BY COUNT(*) DESC
LIMIT 10;
