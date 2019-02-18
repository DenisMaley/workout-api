CREATE DATABASE workout;

USE workout;

CREATE TABLE IF NOT EXISTS `plans` (
  `plan_id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `plan_name`        VARCHAR(256) NOT NULL,
  `plan_description` TEXT         NOT NULL,
  `plan_created`     DATETIME     NOT NULL,
  `plan_modified`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`plan_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `days` (
  `day_id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `day_name`        VARCHAR(256) NOT NULL,
  `day_description` TEXT         NOT NULL,
  PRIMARY KEY (`day_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `exercises` (
  `exercise_id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `exercise_name`        VARCHAR(256) NOT NULL,
  `exercise_muscle`      VARCHAR(256) NOT NULL,
  `exercise_description` TEXT         NOT NULL,
  PRIMARY KEY (`exercise_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `plans_to_days` (
  `plan_id`   INT(11)          NOT NULL,
  `day_id`    INT(11)          NOT NULL,
  `day_index` INT(11) UNSIGNED NOT NULL,
  UNIQUE KEY `uk_pd_plan_id_and_day_index` (`plan_id`, `day_index`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `days_to_exercises` (
  `day_id`         INT(11)          NOT NULL,
  `exercise_id`    INT(11)          NOT NULL,
  `exercise_index` INT(11) UNSIGNED NOT NULL,
  `exercise_sets`  INT(11) UNSIGNED NOT NULL,
  `exercise_reps`  INT(11) UNSIGNED NOT NULL,
  UNIQUE KEY `uk_de_day_id_and_exercixe_index` (`day_id`, `exercise_index`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `user_firstname` VARCHAR(256) NOT NULL,
  `user_lastname`  VARCHAR(256) NOT NULL,
  `user_email`     VARCHAR(256) NOT NULL,
  `plan_id`        INT(11),
  PRIMARY KEY (`user_id`),
  UNIQUE (`user_email`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

ALTER TABLE `plans_to_days`
  ADD CONSTRAINT `fk_ptd_plan_id` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ptd_day_id` FOREIGN KEY (`day_id`) REFERENCES `days` (`day_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `days_to_exercises`
  ADD CONSTRAINT `fk_dte_day_id` FOREIGN KEY (`day_id`) REFERENCES `days` (`day_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dte_exercise_id` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`exercise_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_description`, `plan_created`, `plan_modified`) VALUES
  (1, 'Beginner full body workout routine',
   'To start with, we’ll be taking a look at a beginner workout routine. This workout isn’t too difficult; though, for those new to health and fitness, it will certainly prove challenging.',
   '2018-12-01 00:35:07', '2019-02-06 17:34:33'),
  (2, 'Intermediate workout for men',
   'This next workout is ideal for those of you who are advanced enough to challenge yourselves in the gym without going crazy.This workout routine will help you burn a steady amount of fat without burning yourself out in the process. It is a typical 5 day split that will yield impressive muscle gains.',
   '2018-12-01 00:35:07', '2019-02-02 17:34:33'),
  (3, 'Advanced workout routine for men',
   'Now it’s time for us to take a look at the more advanced workout routine. This routine will really separate the men from the boys.It is high intensity, includes a lot of heavy lifting, and you should aim for minimal rest between sets.Here you will be training for 6 days per week, with just one day of recovery. It may sound brutal, but if you stick with it you will soon be reaping the rewards of an incredible physique.',
   '2018-12-01 00:35:07', '2019-02-03 17:34:54'),
  (4, 'Intermediate workout for women',
   'This next workout is ideal for those of you who are advanced enough to challenge yourselves in the gym without going crazy.This workout routine will help you burn a steady amount of fat without burning yourself out in the process. It is a typical 5 day split that will yield impressive muscle gains.',
   '2018-12-01 12:24:07', '2019-02-04 17:34:33'),
  (5, 'Advanced workout routine for women',
   'Now it’s time for us to take a look at the more advanced workout routine. This routine will really separate the women from the girls.It is high intensity, includes a lot of heavy lifting, and you should aim for minimal rest between sets.Here you will be training for 6 days per week, with just one day of recovery. It may sound brutal, but if you stick with it you will soon be reaping the rewards of an incredible physique.',
   '2018-12-01 12:24:07', '2019-02-05 17:34:33'),
  (6, 'Fitness boot camp',
   'A fitness boot camp is a type of group physical training program conducted by gyms, personal trainers, and former military personnel. These programs are designed to build strength and fitness through a variety of types of exercise.',
   '2018-12-03 13:24:07', '2019-02-06 15:34:33');

INSERT INTO `days` (`day_id`, `day_name`, `day_description`) VALUES
  (1, 'Rest', 'It’s your rest day. Rest your muscle to prepare for the next round of training.'),
  (2, 'Chest, Back, Shoulders, Legs, Biceps, Triceps', ''),
  (3, 'Legs, Triceps, Biceps, Chest, Back, Shoulder', ''),
  (4, 'Shoulders, Back, Chest, Legs, Triceps, Biceps', ''),
  (5, 'Chest, Shoulders and Triceps', ''),
  (6, 'Back and Biceps', ''),
  (7, 'Legs', ''),
  (8, 'Shoulders, Chest, and Triceps', 'Every second week superset bench press and dumbbell flys.Crossovers: Ultra slow rep timing with 2 second pause and squeeze at the top of the movement.'),
  (9, 'Back and Bis', ''),
  (10, 'Chest & Back', ''),
  (11, 'Legs', ''),
  (12, 'Shoulders & Arms', ''),
  (13, 'Chest, Shoulders, & Triceps', ''),
  (14, 'Back & Biceps', ''),
  (15, 'Legs', '');

INSERT INTO `exercises` (`exercise_id`, `exercise_name`, `exercise_muscle`, `exercise_description`) VALUES
  (1, 'Barbell Bench Press', 'Chest', ''),
  (2, 'Lat-pulldowns', 'Back', ''),
  (3, 'Seated Dumbbell Press', 'Shoulders', ''),
  (4, 'Leg Extensions', 'Legs', ''),
  (5, 'Barbell Bbicep Curls', 'Biceps', ''),
  (6, 'Triceps Rope Pushdowns', 'Triceps', ''),
  (7, 'Leg Press Machine', 'Legs', ''),
  (8, 'Overhead Bar Extensions', 'Triceps', ''),
  (9, 'EZ Bar Curls', 'Biceps', ''),
  (10, 'Machine Chest Press', 'Chest', ''),
  (11, 'T-Bar Row', 'Back', ''),
  (12, 'Lateral Raises', 'Shoulders', ''),
  (13, 'EZ Bar Upright Rows', 'Shoulders', ''),
  (14, 'Close-Grip Pulldowns', 'Back', ''),
  (15, 'Cable Fly', 'Chest', ''),
  (16, 'Lunges', 'Legs', ''),
  (17, 'Hammer Curls', 'Biceps', ''),
  (18, 'Tricep Extension', 'Triceps', ''),
  (19, 'Standing Barbell Curl', 'Biceps', ''),
  (20, 'Preacher Curl', 'Biceps', ''),
  (21, 'Incline Dumbbell Curl', 'Biceps', ''),
  (22, 'Squat', 'Quads, Glutes and Hamstrings', ''),
  (23, 'Dumbbell Lunge', 'Quads, Glutes and Hamstrings', ''),
  (24, '45 Degree Leg Press', 'Quads, Glutes and Hamstrings', ''),
  (25, 'Leg Curl', 'Quads, Glutes and Hamstrings', ''),
  (26, 'Leg Extension', 'Quads, Glutes and Hamstrings', ''),
  (27, 'Standing Calf Raise', 'Calves', ''),
  (28, 'Seated Calf Raise', 'Calves', ''),
  (29, 'Dumbbell Flys', 'Chest', ''),
  (30, 'Cable Crossovers', 'Chest', ''),
  (31, 'Close Grip Bench Press', 'Triceps', ''),
  (32, 'Lying Dumbbell Extension', 'Triceps', ''),
  (33, 'Tricep Kickback', 'Triceps', ''),
  (34, 'One Arm Cable Lateral Raise', 'Shoulders', ''),
  (35, 'Seated Row', 'Back', ''),
  (36, 'Bent Over Barbell Row', 'Back', ''),
  (37, 'Bent Over Row', 'Back', ''),
  (38, 'Smith Machine Upright Row', 'Back', ''),
  (39, 'Cable Curl', 'Biceps', ''),
  (40, 'Concentration Curl', 'Biceps', ''),
  (41, 'Reverse Barbell Curl', 'Biceps', ''),
  (42, 'Dumbbell Bench Press', 'Chest', ''),
  (43, 'Incline Dumbbell Bench Press', 'Chest', ''),
  (44, 'Chest Dip', 'Chest', ''),
  (45, 'Skullcrushers', 'Triceps', ''),
  (46, 'One Arm Dumbbell Extension', 'Triceps', ''),
  (47, 'Triceps Extension', 'Triceps', ''),
  (48, 'Barbell Front Raise', 'Shoulders', ''),
  (49, 'Dumbbell Lateral Raise', 'Shoulders', '');

INSERT INTO `plans_to_days` (`plan_id`, `day_id`, `day_index`) VALUES
  (1, 2, 1),
  (1, 3, 2),
  (1, 4, 3),
  (1, 5, 4),
  (1, 6, 5),
  (1, 1, 6),
  (1, 1, 7),
  (2, 10, 1),
  (2, 11, 2),
  (2, 12, 3),
  (2, 13, 4),
  (2, 14, 5),
  (2, 1, 6),
  (2, 1, 7),
  (3, 4, 1),
  (3, 14, 2),
  (3, 15, 3),
  (3, 13, 4),
  (3, 14, 5),
  (3, 2, 6),
  (3, 1, 7),
  (4, 5, 1),
  (4, 12, 2),
  (4, 5, 3),
  (4, 3, 4),
  (4, 9, 5),
  (4, 1, 6),
  (4, 1, 7),
  (5, 6, 1),
  (5, 2, 2),
  (5, 14, 3),
  (5, 7, 4),
  (5, 8, 5),
  (5, 3, 6),
  (5, 1, 7);

INSERT INTO `days_to_exercises` (`day_id`, `exercise_id`, `exercise_index`, `exercise_sets`, `exercise_reps`) VALUES
  (2, 1, 1, 2, 12),
  (2, 11, 2, 3, 12),
  (2, 12, 3, 2, 15),
  (2, 7, 4, 2, 20),
  (2, 9, 5, 4, 15),
  (2, 18, 6, 4, 12),
  (3, 7, 1, 3, 15),
  (3, 47, 2, 4, 12),
  (3, 21, 3, 3, 15),
  (3, 44, 4, 2, 12),
  (3, 36, 5, 3, 20),
  (3, 34, 6, 2, 25),
  (4, 13, 1, 2, 14),
  (4, 35, 2, 3, 12),
  (4, 29, 3, 4, 15),
  (4, 7, 4, 3, 15),
  (4, 18, 5, 4, 20),
  (4, 19, 6, 2, 12),
  (5, 30, 1, 2, 12),
  (5, 42, 2, 4, 14),
  (5, 34, 3, 3, 15),
  (5, 48, 4, 2, 20),
  (5, 45, 5, 4, 25),
  (6, 37, 1, 2, 12),
  (6, 38, 2, 4, 12),
  (6, 20, 3, 3, 15),
  (6, 41, 4, 2, 20),
  (7, 4, 1, 2, 15),
  (7, 7, 2, 3, 15),
  (7, 16, 3, 3, 15),
  (8, 49, 1, 2, 12),
  (8, 3, 2, 3, 15),
  (8, 42, 3, 5, 10),
  (8, 46, 4, 3, 20),
  (9, 11, 1, 2, 14),
  (9, 14, 2, 3, 15),
  (10, 1, 1, 3, 12),
  (10, 30, 2, 2, 12),
  (10, 38, 3, 3, 20),
  (11, 7, 1, 2, 15),
  (11, 16, 2, 3, 20),
  (12, 13, 1, 2, 12),
  (12, 12, 2, 3, 15),
  (12, 20, 3, 4, 20),
  (12, 8, 4, 2, 15),
  (13, 44, 1, 2, 12),
  (13, 49, 2, 3, 15),
  (13, 6, 3, 4, 12),
  (14, 2, 1, 2, 12),
  (14, 11, 2, 4, 15),
  (14, 19, 3, 5, 12),
  (14, 21, 4, 3, 20),
  (15, 23, 1, 3, 30),
  (15, 24, 2, 3, 20),
  (15, 25, 3, 3, 25),
  (15, 26, 4, 3, 20);
