CREATE TABLE Movie (
  id INT,
  title VARCHAR(100) NOT NULL,
  year INT NOT NULL,
  rating VARCHAR(10),
  company VARCHAR(50),
  PRIMARY KEY (id) -- A Movie's id should be unique
);

CREATE TABLE Actor (
  id INT,
  last VARCHAR(20),
  first VARCHAR(20),
  sex VARCHAR(6),
  dob DATE NOT NULL,
  dod DATE,
  PRIMARY KEY (id), -- An Actor's id should be unique
  CHECK (dod IS NULL OR dod > dob) -- An Actor should die after they are born
);

CREATE TABLE Director (
  id INT,
  last VARCHAR(20),
  first VARCHAR(20),
  dob DATE NOT NULL,
  dod DATE,
  PRIMARY KEY (id), -- A Director's id should be unique
  CHECK (
    id NOT IN (
      SELECT id
      FROM Actor
    ) OR id IN (
      SELECT id
      FROM Actor A
      WHERE last = A.last
        AND first = A.first
        AND dob = A.dob
        AND dod = A.dod
    )
  ), -- A Director's name and date of birth/death should match those of an Actor with the same id
  CHECK (dod IS NULL OR dod > dob) -- A Director should die after they are born
);

CREATE TABLE MovieGenre (
  mid INT,
  genre VARCHAR(20),
  PRIMARY KEY (mid), -- A MovieGenre's mid should be unique
  FOREIGN KEY (mid) REFERENCES Movie(id) -- A MovieGenre's mid should exist as a Movie's id
) ENGINE = INNODB;

CREATE TABLE MovieDirector (
  mid INT,
  did INT,
  PRIMARY KEY (mid, did),
  FOREIGN KEY (mid) REFERENCES Movie(id), -- A MovieDirectors's mid should exist as a Movie's id
  FOREIGN KEY (did) REFERENCES Director(id) -- A MovieDirector's did should exists as a Director's id
) ENGINE = INNODB;

CREATE TABLE MovieActor (
  mid  INT,
  aid INT,
  role VARCHAR(50),
  PRIMARY KEY (mid, aid),
  FOREIGN KEY (mid) REFERENCES Movie(id), -- A MovieActor's mid should exist as a Movie's id
  FOREIGN KEY (aid) REFERENCES Actor(id) -- A MovieActor's aid should exist as an Actor's id
) ENGINE = INNODB;

CREATE TABLE Review (
  name VARCHAR(20),
  time TIMESTAMP,
  mid INT,
  rating INT NOT NULL,
  comment VARCHAR(500),
  PRIMARY KEY (name, time, mid),
  FOREIGN KEY (mid) REFERENCES Movie(id), -- A Review's mid should exist as a Movie's id
  CHECK (rating <= 5 AND rating >= 0) -- A rating must be between 0 and 5 inclusive
);

CREATE TABLE MaxPersonID (
  id INT,
  PRIMARY KEY (id) -- A MaxPersonID id should be unique
);

INSERT INTO MaxPersonID VALUES (69000);

CREATE TABLE MaxMovieID (
  id INT,
  PRIMARY KEY (id) -- A MaxMovieID id should be unique
);

INSERT INTO MaxMovieID VALUES (4750);
