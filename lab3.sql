USE demo;
CREATE TABLE lab3 (
	id              int(11)      not null auto_increment,
	first_name      varchar(50)  not null,
	last_name       varchar(50)  not null,
	email           varchar(128) not null,
	email_personal  int(2)       not null,
	phone           varchar(20)  not null,
	phone_personal  int(2)       not null,
	primary key (id)
);
