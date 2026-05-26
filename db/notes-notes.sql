-- -------------------------------------------------------------
-- -------------------------------------------------------------
-- TablePlus 1.5.5
--
-- https://tableplus.com/
--
-- Database: kampusdb
-- Generation Time: 2026-05-26 14:26:09.531066
-- -------------------------------------------------------------

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS notes_id_seq;

-- Table Definition
CREATE TABLE "public"."notes" (
    "id" int4 NOT NULL DEFAULT nextval('notes_id_seq'::regclass),
    "content" text NOT NULL,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    "user_id" int4 NOT NULL,
    PRIMARY KEY ("id")
);

INSERT INTO "public"."notes" ("id","content","created_at","user_id") VALUES 
(1,'test fix','2026-04-14 09:22:14.237185',1),
(2,'halo','2026-04-14 09:23:36.232557',1),
(3,'from-test','2026-04-14 09:27:17.86531',1),
(4,'testing','2026-04-21 09:34:15.893691',1),
(5,'halo saya anak it','2026-04-21 09:42:32.431672',2),
(6,'nama saya zidan','2026-04-21 10:05:02.783942',3),
(7,'testing notes','2026-04-27 13:22:26.234076',3),
(8,'saya mahasiswa PENS prodi IT','2026-04-28 09:17:56.432661',3);

