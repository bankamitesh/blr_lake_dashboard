

DROP TABLE IF EXISTS  sc_php_session ;
CREATE TABLE  sc_php_session  (
       session_id  varchar(40) NOT NULL DEFAULT '',
       data  text,
       updated_on  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY ( session_id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


drop table if exists atree_lake;
create table atree_lake(
    id int NOT NULL auto_increment,
    name varchar(64) not null,
    cname varchar(64) not null,
    about varchar(512),
    lat varchar(16),
    lon varchar(16),
    address varchar(256),
    max_area varchar(16),
    max_volume varchar(16),
    recharge_rate varchar(16),
    agency_code tinyint default 1 ,
    type_code tinyint default 1 ,
    usage_code varchar(256), 
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;
