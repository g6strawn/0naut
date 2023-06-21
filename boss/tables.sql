-- --------------------------------------------------------------------------
-- registered users
-- RENAME TABLE users TO users_old;
DROP TABLE IF EXISTS users;
CREATE TABLE users (
user_id    INT UNSIGNED  NOT NULL AUTO_INCREMENT,
email      VARCHAR(254)  NOT NULL              COMMENT 'Unique email',
pswdhash   VARCHAR(255)  NOT NULL              COMMENT 'Password hash',
autologin  VARCHAR(255)  DEFAULT NULL          COMMENT 'Remember me hash',
flags      INT UNSIGNED  NOT NULL DEFAULT 0    COMMENT 'Access flags',
created    DATETIME DEFAULT CURRENT_TIMESTAMP  COMMENT 'Account creation date',
visited    DATETIME DEFAULT CURRENT_TIMESTAMP  COMMENT 'Most recent login',
notify     BOOLEAN NOT NULL DEFAULT TRUE       COMMENT 'Receive emails?',

first_name VARCHAR(20) NOT NULL,
last_name  VARCHAR(30) NOT NULL,
company    VARCHAR(50),
street     VARCHAR(50),
street2    VARCHAR(50),
city       VARCHAR(40),
state      VARCHAR(20),
zip        VARCHAR(10),
country    VARCHAR(20),
phone      VARCHAR(20),
phone2     VARCHAR(20),
remarks    TEXT COMMENT 'User comments',
notes      TEXT COMMENT 'Private admin notes',

PRIMARY KEY (user_id),
UNIQUE KEY (email),
KEY (autologin),
KEY (created),
KEY (visited)
) ENGINE=InnoDB;


-- Reserved accounts, no login allowed!  flags=3=Banned & Watched (notify if login)
--   Some of these account correspond to email addresses used by server.
--   SELECT * FROM users WHERE flags=3 AND created=visited;
-- thunk   - Account reserved. Cannot login. Username/email can be used.
-- root    - Never  used. This is a decoy account for hackers
-- info    - Contact Us;  Reply-To: address for all emails sent by server
-- admin   - From: address for all emails sent by server. Only server has password to this account.
--           Email receives activity notifications: new user, pswd reset, watched/inactive login, etc.
-- php     - Email receives server error messages (see ErrMsg() in globals.php)
-- orders  - Email receives new order notifications.
-- cashier - Email receives order problems:  unable to process order, card declined, etc.
-- sales   - not used;  Email might be used for server spam
-- news    - not used;  Email might used for server spam
INSERT INTO users (email, pswdhash, flags, notify, first_name, last_name) VALUES
('thunk@thunkonaut.com',   'notused00_tB0A\mb6$l^.', 3, 0, 'Thunk',  'Naut'),
('root@thunkonaut.com',    'notused01_$m-S97[0((n5', 3, 0, 'root',   ''),
('info@thunkonaut.com',    'notused02_Rf2b0sD/1~9J', 3, 0, 'info',   ''),
('admin@thunkonaut.com',   'notused03_dKz|z86Y+Q&^', 3, 0, 'admin',  ''),
('php@thunkonaut.com',     'notused04_-1O294RT@HzU', 3, 0, 'php',    ''),
('orders@thunkonaut.com',  'notused05_]>e29x=5nj\3', 3, 0, 'orders', ''),
('cashier@thunkonaut.com', 'notused06_XN3he%CxO(]G', 3, 0, 'cashier',''),
('helper@thunkonaut.com',  'notused07_OlMDbF6J<?7r', 3, 0, 'helper', ''),
('news@thunkonaut.com',    'notused08_xaVBNR5858>m', 3, 0, 'news',   ''),
('sales@thunkonaut.com',   'notused09_>PaO1{s|c7dZ', 3, 0, 'sales',  '');

-- flags:  Dev & Wholesale = 0xF080 = 61568;  Admin = 0x8080 = 32896
INSERT INTO users (email, pswdHash, flags, notify, first_name, last_name, street, street2, city, state, zip, phone) VALUES
('g6strawn@gmail.com','$2y$10$rvIwm4AoaR6NjYz01DNpYOTVEb2uIbuZHGgzZ2CSUaDbyYBu7kM46',
0xF080, 1, 'Gary', 'Strawn', '1855 Cherry Blossom Dr', '203', 'Windsor', 'CO', '80550', '808-895-7096');


-- --------------------------------------------------------------------------
-- phone - calls, texts, phone_names - phone voice calls & text message logs
DROP TABLE IF EXISTS phone;
CREATE TABLE phone (
id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
num_id INT UNSIGNED NOT NULL,
time   DATETIME     NOT NULL,
dest   VARCHAR(30)  NOT NULL DEFAULT '',
min    INT UNSIGNED NOT NULL DEFAULT 0,
dir    ENUM('', 'Incoming', 'Outgoing') NOT NULL DEFAULT '',
type   ENUM('Call', 'TMobile', 'WiFi', 'Call waiting', 'Text', 'Picture', 'Unknown') NOT NULL,
PRIMARY KEY (id),
KEY(num_id),
KEY(time)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS phone_names;
CREATE TABLE phone_names (
  id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  num  VARCHAR(15)  NOT NULL UNIQUE,
  name VARCHAR(50)  NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- hits - list of website hits
DROP TABLE IF EXISTS hits;
CREATE TABLE hits (
hit_id   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
hit_time DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
ip       VARBINARY(16) NOT NULL DEFAULT 0 COMMENT 'Use INET6_NTOA',
user_id  INT UNSIGNED  NOT NULL DEFAULT 0,
page     VARCHAR(255),
msg      TEXT,
PRIMARY KEY (hit_id),
KEY (hit_time)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- throttle - list of website throttle events: failed login, banned user checkout, etc.
DROP TABLE IF EXISTS throttle;
CREATE TABLE throttle (
hit_id   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
hit_time DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
ip       VARBINARY(16) NOT NULL DEFAULT 0 COMMENT 'Use INET6_NTOA',
delay    INT UNSIGNED  NOT NULL DEFAULT 0,
msg      VARCHAR(255),
PRIMARY KEY (hit_id),
KEY (hit_time)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- book_comments - comments left by users
-- Note: user_id is NOT auto_increment because user_id is already unique
DROP TABLE IF EXISTS book_comments;
CREATE TABLE book_comments (
user_id  INT UNSIGNED NOT NULL,
comment  TEXT,
updated  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (user_id)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- cicada_puzzle - track user progress
DROP TABLE IF EXISTS cicada_puzzle;
CREATE TABLE `cicada_puzzle` (
  `puzz_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT  COMMENT 'one per attempt',
  `cookie_id` VARCHAR(256) NOT NULL DEFAULT ''  COMMENT 'sha256(cookie id)',
  `prev_id`   INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'previous attempt',
  `email`     VARCHAR(254) NOT NULL             COMMENT 'anonymous email',
  `started`   DATETIME     NOT NULL             COMMENT 'start time',
  `updated`   DATETIME     NOT NULL             COMMENT 'most recent activity',
  `level`     INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'most recently completed',
  `maxlvl`    INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'highest level completed',
  `hints`     INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# hints, total',
  `lvlhint`   INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# hints for current level',
  `cheats`    INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '# cheats; 0=honest',
  `remail_id` CHAR(16)     NOT NULL DEFAULT ''  COMMENT 'level 16 Remail id',
  PRIMARY KEY (`puzz_id`),
  KEY (`maxlvl`),
  KEY (`hints`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS cicada_puzzle_times;
CREATE TABLE `cicada_puzzle_times` (
  `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `puzz_id` INT UNSIGNED NOT NULL COMMENT 'key into CicadaPuzzle',
  `time`    DATETIME     NOT NULL COMMENT 'completion time',
  `level`   INT UNSIGNED NOT NULL COMMENT 'level (clue) completed',
  PRIMARY KEY (`id`),
  KEY (`puzz_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS cicada_test_answers;
CREATE TABLE `cicada_test_answers` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `puzz_id`  INT UNSIGNED NOT NULL COMMENT 'key into CicadaPuzzle',
  `time`     DATETIME     NOT NULL COMMENT 'answered time',
  `question` INT UNSIGNED NOT NULL COMMENT 'question 0-18',
  `answer`   ENUM('true','false','indeterminate','meaningless','self-referential','rule','loop','none','reflection','fish','neither','both') DEFAULT NULL,
  `essay`    VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`puzz_id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- market_scores - Beat the Market (see invest.js)
DROP TABLE IF EXISTS market_scores;
CREATE TABLE market_scores (
id      INT UNSIGNED  NOT NULL AUTO_INCREMENT,
uid     VARCHAR(13)   NOT NULL UNIQUE,
name    VARCHAR(10)   NOT NULL DEFAULT '',
played  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
start   VARCHAR(10)   NOT NULL DEFAULT '',
days    INT UNSIGNED  NOT NULL DEFAULT 0,
youCash INT           NOT NULL DEFAULT 0,
avgCash INT           NOT NULL DEFAULT 0,
youGain DECIMAL(10,1) NOT NULL DEFAULT 0,
avgGain DECIMAL(10,1) NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY (uid),
KEY (played)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- pow2_scores - Powers of 2 (1024) game (see pow2.js)
DROP TABLE IF EXISTS pow2_scores;
CREATE TABLE pow2_scores (
id       INT UNSIGNED  NOT NULL AUTO_INCREMENT,
uid      VARCHAR(32)   NOT NULL UNIQUE,
name     VARCHAR(10)   NOT NULL DEFAULT '',
started  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
score    INT UNSIGNED  NOT NULL DEFAULT 0,
level    INT UNSIGNED  NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY (uid),
KEY (score)
) ENGINE=InnoDB;


-- --------------------------------------------------------------------------
-- xkcd1190 - xkcd #1190 epic adventure
CREATE TABLE IF NOT EXISTS `xkcd1190` (
  `frame` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `xlink` VARCHAR(150) NOT NULL COMMENT 'xkcd url',
  `delay` INT UNSIGNED          COMMENT 'ms delay until next frame',
  `ticks` INT UNSIGNED NOT NULL COMMENT 'frame timestamp (ms)',
  PRIMARY KEY (`frame`)
) ENGINE=InnoDB;
