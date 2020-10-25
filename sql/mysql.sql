CREATE TABLE eEmpregos_listing (
    lid          INT(11)      NOT NULL AUTO_INCREMENT,
    cid          INT(11)      NOT NULL DEFAULT '0',
    title        VARCHAR(100) NOT NULL DEFAULT '',
    type         VARCHAR(100) NOT NULL DEFAULT '',
    company      VARCHAR(100) NOT NULL DEFAULT '',
    description  TEXT         NOT NULL,
    requirements TEXT         NOT NULL,
    tel          VARCHAR(15)  NOT NULL DEFAULT '',
    price        VARCHAR(100) NOT NULL DEFAULT '',
    typeprice    VARCHAR(100) NOT NULL DEFAULT '',
    contactinfo  MEDIUMTEXT   NOT NULL,
    date         VARCHAR(25)           DEFAULT NULL,
    email        VARCHAR(100) NOT NULL DEFAULT '',
    submitter    VARCHAR(60)  NOT NULL DEFAULT '',
    usid         VARCHAR(6)   NOT NULL DEFAULT '',
    town         VARCHAR(200) NOT NULL DEFAULT '',
    valid        VARCHAR(11)  NOT NULL DEFAULT '',
    photo        VARCHAR(100) NOT NULL DEFAULT '',
    view         VARCHAR(10)  NOT NULL DEFAULT '0',
    PRIMARY KEY (lid)
)
    ENGINE = ISAM;

# Dumping data for table `eEmpregos_listing`
#

INSERT INTO eEmpregos_listing
VALUES (2, 1, 'Example Job', 'Full Time', 'Example Company', 'Here you can put a complete description of the job you are offering.', 'Here you can put all the requirements you have for the Job being offered.', '', '16.00', 'Per Hour',
        'Some Examples would be:\r\n\r\n1. Send Resume to:\r\n   Example Company\r\n   22 Example Adrress\r\n   Southington, Ct. 06489\r\n\r\n2. Reply in person', '1083798448', 'john@connectunet.com', 'john', '1', 'Southington', 'Yes', '', '0');
# --------------------------------------------------------

#
# Table structure for table `eEmpregos_price`

CREATE TABLE eEmpregos_categories (
    cid      INT(11)         NOT NULL AUTO_INCREMENT,
    pid      INT(5) UNSIGNED NOT NULL DEFAULT '0',
    title    VARCHAR(50)     NOT NULL DEFAULT '',
    img      VARCHAR(150)    NOT NULL DEFAULT '',
    ordre    INT(5)          NOT NULL DEFAULT '0',
    affprice INT(5)          NOT NULL DEFAULT '0',
    PRIMARY KEY (cid)
)
    ENGINE = ISAM;

INSERT INTO eEmpregos_categories
VALUES (1, 0, 'Job Listings', 'default.gif', 0, 1);

CREATE TABLE eEmpregos_type (
    id_type  INT(11)      NOT NULL AUTO_INCREMENT,
    nom_type VARCHAR(150) NOT NULL DEFAULT '',
    PRIMARY KEY (id_type)
)
    ENGINE = ISAM;


INSERT INTO eEmpregos_type
VALUES (1, 'Full Time');
INSERT INTO eEmpregos_type
VALUES (2, 'Part Time');


CREATE TABLE eEmpregos_price (
    id_price  INT(11)      NOT NULL AUTO_INCREMENT,
    nom_price VARCHAR(150) NOT NULL DEFAULT '',
    PRIMARY KEY (id_price)
)
    ENGINE = ISAM;


INSERT INTO eEmpregos_price
VALUES (1, 'Per Hour');
INSERT INTO eEmpregos_price
VALUES (2, 'Annual');
