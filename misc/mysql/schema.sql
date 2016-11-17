

DROP TABLE IF EXISTS  sc_php_session ;
CREATE TABLE  sc_php_session  (
       session_id  varchar(40) NOT NULL DEFAULT '',
       data  text,
       updated_on  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY ( session_id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

