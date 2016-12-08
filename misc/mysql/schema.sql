

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


drop table if exists atree_lake_file;
create table atree_lake_file(
    id int NOT NULL auto_increment,
    lake_id int not null ,
    file_id int not null ,
    file_code int not null,
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;


-- inlet/outlet : is feature_class
-- storm water/Drain etc. is feature_type_code 
-- 
drop table if exists atree_lake_feature;
create table atree_lake_feature(
    id int NOT NULL auto_increment,
    lake_id int not null ,
    name varchar(64) not null,
    io_code tinyint default 1,
    feature_type_code tinyint ,
    monitoring_code tinyint,
    lat varchar(16),
    lon varchar(16),
    width int ,
    max_height int ,
    flow_rate varchar(16), 
    lake_flow_file_id int,
    sensor_flow_file_id int, 
    sensor_data varchar(1024),
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;


drop table if exists atree_feature_sensor;
create table atree_feature_sensor(
    id int NOT NULL auto_increment,
    feature_id int not null ,
    sensor_id int not null,
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;


drop table if exists atree_sensor;
create table atree_sensor(
    id int NOT NULL auto_increment,
    serial_number varchar(36),
    part_number varchar(36),
    installer_name varchar(36),
    installation_date timestamp,
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;

alter table atree_sensor add constraint UNIQUE(serial_number);


-- 
-- add an insert trigger
-- 
drop table if exists atree_lake_counter;
create table atree_lake_counter(
    id int NOT NULL auto_increment,
    lake_id int not null,
    inlet_count int default 0,
    outlet_count int default 0,
    sensor_count int default 0,
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;



drop table if exists atree_file_blob;
create table atree_file_blob (
    id int NOT NULL auto_increment,
    file_blob MEDIUMBLOB ,
    file_code varchar(16) ,
    file_size int,
    file_name varchar(64),
    mime varchar(128),
    login_id int,
    email varchar(64),
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;


drop table if exists atree_lake_zone;
create table atree_lake_zone(
    id int NOT NULL auto_increment,
    lake_id int not null,
    html text,
    description varchar(512) ,
    created_on timestamp default current_timestamp,
    updated_on timestamp default current_timestamp ,
    PRIMARY KEY (id)) ENGINE = InnoDB default character set utf8 collate utf8_general_ci;
