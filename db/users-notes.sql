-- -------------------------------------------------------------
-- -------------------------------------------------------------
-- TablePlus 1.5.5
--
-- https://tableplus.com/
--
-- Database: kampusdb
-- Generation Time: 2026-05-26 14:26:22.146058
-- -------------------------------------------------------------

-- This script only contains the table creation statements and does not fully represent the table in database. It's still missing: indices, triggers. Do not use it as backup.

-- Sequences
CREATE SEQUENCE IF NOT EXISTS users_id_seq;

-- Table Definition
CREATE TABLE "public"."users" (
    "id" int4 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    "username" varchar NOT NULL,
    "password_hash" text NOT NULL,
    "created_at" timestamp NOT NULL DEFAULT now(),
    PRIMARY KEY ("id")
);

INSERT INTO "public"."users" ("id","username","password_hash","created_at") VALUES 
(1,'admin','$2y$12$VRcCdifhvCo2uVZLBkAY.eZFuA2XKG82I0MC2kw/ODaXMjbUTX4iK','2026-04-21 09:26:25.961087'),
(2,'hafiz','$2y$12$FENX7LqeF2wKo2pzLz5Z/eewC2Wp9pTXpO0YBw2y8AtkrhM0K9KK.','2026-04-21 09:42:07.96702'),
(3,'zidan','$2y$12$AXf1igAJy0sLT30Ze29CXOYdXGlbjGvaIVgm4AaC0stMFBcnhoOdW','2026-04-21 10:04:42.767673');

