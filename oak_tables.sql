CREATE TABLE oak_ci_session (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(16) DEFAULT '0' NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_agent varchar(120) NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY(session_id),
	KEY last_activity_idx (last_activity)
);

CREATE TABLE oak_comment (
	id int(11) NOT NULL AUTO_INCREMENT,
	active int(1) NOT NULL,
	body text NOT NULL,
	date_created int(11) NOT NULL,
	habit_id int(11) NOT NULL,
	user_id int(11) NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE oak_cookie (
	date_created int(11) NOT NULL,
	token varchar(128) NOT NULL,
	username varchar(128) NOT NULL,
	PRIMARY KEY(token, username)
);

CREATE TABLE oak_entry (
	date_recorded int(11) NOT NULL,
	task_id int(11) NOT NULL,
	PRIMARY KEY(date_recorded, task_id)
);

CREATE TABLE oak_favourite (
    active int(1) NOT NULL DEFAULT '1',
    date_created int(11) NOT NULL,
    habit_id int(11) NOT NULL,
    user_id int(11) NOT NULL,
    PRIMARY KEY(habit_id, user_id)
);

CREATE TABLE oak_habit (
	id int(11) NOT NULL AUTO_INCREMENT,
	active int(1) NOT NULL,
	date_created int(11) NOT NULL,
	description text,
	name varchar(128) NOT NULL,
	slug varchar(128) NOT NULL,
	user_id int(11) NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE oak_note (
	id int(11) NOT NULL AUTO_INCREMENT,
	active int(1) NOT NULL,
	body text NOT NULL,
	date_created int(11) NOT NULL,
	task_id int(11) NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE oak_task (
	id int(11) NOT NULL AUTO_INCREMENT,
	active int(1) NOT NULL,
	date_archived int(11),
	date_started int(11) NOT NULL,
	habit_id int(11) NOT NULL,
	user_id int(11) NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE oak_user (
	id int(11) NOT NULL AUTO_INCREMENT,
	active int(1) NOT NULL,
	date_registered int(11) NOT NULL,
	date_signin int(11) NOT NULL,
	name varchar(128) NOT NULL,
	oauth_id int(11),
	oauth_token varchar(128),
	oauth_token_secret varchar(128),
	profile_image_url varchar(128),
	username varchar(128) NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO oak_comment SET active = 1, body = 'I was born in the year 1632, in the city of York, of a good family, though not of that country, my father being a foreigner of Bremen, who settled first at Hull.', date_created = 1335740400, habit_id = 1, user_id = 1;

INSERT INTO oak_comment SET active = 1, body = 'I had two elder brothers, one of whom was lieutenant-colonel to an English regiment of foot in Flanders, formerly commanded by the famous Colonel Lockhart, and was killed at the battle near Dunkirk against the Spaniards.', date_created = 1337247199, habit_id = 1, user_id = 1;

INSERT INTO oak_entry SET date_recorded = 1335740400, task_id = 1;

INSERT INTO oak_entry SET date_recorded = 1335826800, task_id = 1;

INSERT INTO oak_entry SET date_recorded = 1335913200, task_id = 1;

INSERT INTO oak_entry SET date_recorded = 1335913200, task_id = 2;

INSERT INTO oak_entry SET date_recorded = 1335913200, task_id = 3;

INSERT INTO oak_habit SET active = 1, date_created = 1335740400, description = 'I was born in the year 1632, in the city of York, of a good family, though not of that country, my father being a foreigner of Bremen, who settled first at Hull.', name = 'Read some code', slug = 'Read-some-code', user_id = 1;

INSERT INTO oak_habit SET active = 1, date_created = 1335740400, description = 'I was born in the year 1632, in the city of York, of a good family, though not of that country, my father being a foreigner of Bremen, who settled first at Hull.', name = 'Write', slug = 'Write', user_id = 1;

INSERT INTO oak_habit SET active = 1, date_created = 1335740400, description = 'I was born in the year 1632, in the city of York, of a good family, though not of that country, my father being a foreigner of Bremen, who settled first at Hull.', name = 'Solve a math problem', slug = 'Solve-a-math-problem', user_id = 1;

INSERT INTO oak_note SET active = 1, body = 'I was born in the year 1632, in the city of York, of a good family, though not of that country, my father being a foreigner of Bremen, who settled first at Hull.', date_created = 1335740400, task_id = 1;

INSERT INTO oak_task SET active = 1, date_started = 1335740400, habit_id = 1, user_id = 1;

INSERT INTO oak_task SET active = 1, date_started = 1335740400, habit_id = 2, user_id = 1;

INSERT INTO oak_task SET active = 1, date_started = 1335740400, habit_id = 3, user_id = 1;