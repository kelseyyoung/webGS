BEGIN;
CREATE TABLE `wgsDB_instructor` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `username` varchar(50) NOT NULL UNIQUE
)
;
CREATE TABLE `wgsDB_class_instructors` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `class_id` integer NOT NULL,
    `instructor_id` integer NOT NULL,
    UNIQUE (`class_id`, `instructor_id`)
)
;
ALTER TABLE `wgsDB_class_instructors` ADD CONSTRAINT `instructor_id_refs_id_5b9f8140` FOREIGN KEY (`instructor_id`) REFERENCES `wgsDB_instructor` (`id`);
CREATE TABLE `wgsDB_class` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(50) NOT NULL UNIQUE
)
;
ALTER TABLE `wgsDB_class_instructors` ADD CONSTRAINT `class_id_refs_id_a90b177f` FOREIGN KEY (`class_id`) REFERENCES `wgsDB_class` (`id`);
CREATE TABLE `wgsDB_assignment` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(50) NOT NULL,
    `startDateTime` datetime NOT NULL,
    `endDateTime` datetime NOT NULL,
    `the_class_id` integer NOT NULL,
    `num_testcases` integer NOT NULL,
    `points_per_testcase` integer NOT NULL,
    `total_points` integer NOT NULL
)
;
ALTER TABLE `wgsDB_assignment` ADD CONSTRAINT `the_class_id_refs_id_5af01c59` FOREIGN KEY (`the_class_id`) REFERENCES `wgsDB_class` (`id`);
CREATE TABLE `wgsDB_student_assignments` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `student_id` integer NOT NULL,
    `assignment_id` integer NOT NULL,
    UNIQUE (`student_id`, `assignment_id`)
)
;
ALTER TABLE `wgsDB_student_assignments` ADD CONSTRAINT `assignment_id_refs_id_6703acb4` FOREIGN KEY (`assignment_id`) REFERENCES `wgsDB_assignment` (`id`);
CREATE TABLE `wgsDB_student_classes` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `student_id` integer NOT NULL,
    `class_id` integer NOT NULL,
    UNIQUE (`student_id`, `class_id`)
)
;
ALTER TABLE `wgsDB_student_classes` ADD CONSTRAINT `class_id_refs_id_ea3f2747` FOREIGN KEY (`class_id`) REFERENCES `wgsDB_class` (`id`);
CREATE TABLE `wgsDB_student` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `username` varchar(50) NOT NULL UNIQUE
)
;
ALTER TABLE `wgsDB_student_assignments` ADD CONSTRAINT `student_id_refs_id_94ee8385` FOREIGN KEY (`student_id`) REFERENCES `wgsDB_student` (`id`);
ALTER TABLE `wgsDB_student_classes` ADD CONSTRAINT `student_id_refs_id_20bc9c9a` FOREIGN KEY (`student_id`) REFERENCES `wgsDB_student` (`id`);
CREATE TABLE `wgsDB_score` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `score` integer NOT NULL,
    `student_id` integer NOT NULL,
    `assignment_id` integer NOT NULL
)
;
ALTER TABLE `wgsDB_score` ADD CONSTRAINT `assignment_id_refs_id_d880c562` FOREIGN KEY (`assignment_id`) REFERENCES `wgsDB_assignment` (`id`);
ALTER TABLE `wgsDB_score` ADD CONSTRAINT `student_id_refs_id_fab89b4e` FOREIGN KEY (`student_id`) REFERENCES `wgsDB_student` (`id`);
CREATE TABLE `wgsDB_testcase` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(50) NOT NULL,
    `assignment_id` integer NOT NULL
)
;
ALTER TABLE `wgsDB_testcase` ADD CONSTRAINT `assignment_id_refs_id_6e1c0fef` FOREIGN KEY (`assignment_id`) REFERENCES `wgsDB_assignment` (`id`);
CREATE TABLE `wgsDB_section_students` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `section_id` integer NOT NULL,
    `student_id` integer NOT NULL,
    UNIQUE (`section_id`, `student_id`)
)
;
ALTER TABLE `wgsDB_section_students` ADD CONSTRAINT `student_id_refs_id_98a460f0` FOREIGN KEY (`student_id`) REFERENCES `wgsDB_student` (`id`);
CREATE TABLE `wgsDB_section` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(50) NOT NULL,
    `the_class_id` integer NOT NULL,
    `instructor_id` integer
)
;
ALTER TABLE `wgsDB_section` ADD CONSTRAINT `the_class_id_refs_id_4fafa546` FOREIGN KEY (`the_class_id`) REFERENCES `wgsDB_class` (`id`);
ALTER TABLE `wgsDB_section` ADD CONSTRAINT `instructor_id_refs_id_2609e053` FOREIGN KEY (`instructor_id`) REFERENCES `wgsDB_instructor` (`id`);
ALTER TABLE `wgsDB_section_students` ADD CONSTRAINT `section_id_refs_id_08bd7f29` FOREIGN KEY (`section_id`) REFERENCES `wgsDB_section` (`id`);
CREATE TABLE `wgsDB_submission` (
	`id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
	`score` integer NOT NULL,
	`hints` longtext NOT NULL,
	`student_id` integer NOT NULL,
	`assignment_id` integer NOT NULL,
	`time_submitted` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE `wgsDB_submission` ADD CONSTRAINT `assignment_id_refs_id_d6d82e44` FOREIGN KEY (`assignment_id`) REFERENCES `wgsDB_assignment` (`id`);
ALTER TABLE `wgsDB_submission` ADD CONSTRAINT `student_id_refs_id_ec4e4262` FOREIGN KEY (`student_id`) REFERENCES `wgsDB_student` (`id`);

CREATE INDEX `wgsDB_assignment_e231a574` ON `wgsDB_assignment` (`the_class_id`);
CREATE INDEX `wgsDB_score_94741166` ON `wgsDB_score` (`student_id`);
CREATE INDEX `wgsDB_score_52f7e0e7` ON `wgsDB_score` (`assignment_id`);
CREATE INDEX `wgsDB_testcase_52f7e0e7` ON `wgsDB_testcase` (`assignment_id`);
CREATE INDEX `wgsDB_section_e231a574` ON `wgsDB_section` (`the_class_id`);
CREATE INDEX `wgsDB_section_fdb8591a` ON `wgsDB_section` (`instructor_id`);
CREATE INDEX `wgsDB_submission_94741166` ON `wgsDB_submission` (`student_id`);
CREATE INDEX `wgsDB_submission_52f7e0e7` ON `wgsDB_submission` (`assignment_id`);

COMMIT;
