BEGIN;
ALTER TABLE `wgsDB_section_students` DROP FOREIGN KEY `section_id_refs_id_08bd7f29`;
DROP TABLE `wgsDB_section`;
DROP TABLE `wgsDB_section_students`;
DROP TABLE `wgsDB_testcase`;
DROP TABLE `wgsDB_score`;
ALTER TABLE `wgsDB_student_classes` DROP FOREIGN KEY `student_id_refs_id_20bc9c9a`;
ALTER TABLE `wgsDB_student_assignments` DROP FOREIGN KEY `student_id_refs_id_94ee8385`;
DROP TABLE `wgsDB_student`;
DROP TABLE `wgsDB_student_classes`;
DROP TABLE `wgsDB_student_assignments`;
DROP TABLE `wgsDB_assignment`;
ALTER TABLE `wgsDB_class_instructors` DROP FOREIGN KEY `class_id_refs_id_a90b177f`;
DROP TABLE `wgsDB_class`;
DROP TABLE `wgsDB_class_instructors`;
DROP TABLE `wgsDB_instructor`;

COMMIT;