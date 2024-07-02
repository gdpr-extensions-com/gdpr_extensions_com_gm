CREATE TABLE tx_gdprextensionscomgm_domain_model_map (
	title varchar(255) NOT NULL DEFAULT '',
	icon_path varchar(255) NOT NULL DEFAULT '',
	map_path varchar(255) NOT NULL DEFAULT '',
	locations int(11) unsigned NOT NULL DEFAULT '0',
    root_pid varchar(255) NOT NULL DEFAULT '',
    remote_uid varchar(255) NOT NULL DEFAULT '',
    lat int(11) DEFAULT '0',
    lon int(11) DEFAULT '0',
    zoom int(11) DEFAULT '0',
    dashboard_api_key varchar(255) NOT NULL DEFAULT '',
);

CREATE TABLE tx_gdprextensionscomgm_domain_model_maplocation (
	map int(11) unsigned DEFAULT '0' NOT NULL,
	title varchar(255) NOT NULL DEFAULT '',
	address varchar(255) NOT NULL DEFAULT '',
    lat int(11) NOT NULL DEFAULT '0',
    lon int(11) NOT NULL DEFAULT '0',
    remote_uid varchar(255) NOT NULL DEFAULT '',
);

CREATE TABLE tt_content (
		gdpr_map_business_locations varchar(255) NOT NULL DEFAULT '',
		gdpr_map_error_message varchar(255) NOT NULL DEFAULT '',

);
