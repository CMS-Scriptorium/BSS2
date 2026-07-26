--| Install Struct for the Bakery Module

CREATE TABLE IF NOT EXISTS `{BXT}_items` (
    `item_id`             INT(11) NOT NULL AUTO_INCREMENT,
    `section_id`          INT(11) NOT NULL DEFAULT '0',
    `page_id`             INT(11) NOT NULL DEFAULT '0',
    `group_id`            INT(11) NOT NULL DEFAULT '0',
    `active`              INT(11) NOT NULL DEFAULT '0',
    `position`            INT(11) NOT NULL DEFAULT '0',
    `title`               VARCHAR(255) NULL DEFAULT '',
    `sku`                 VARCHAR(20) NULL DEFAULT '',
    `stock`               VARCHAR(20) NULL DEFAULT '',
    `price`               DECIMAL(9,2) NOT NULL DEFAULT '0.00',
    `shipping`            DECIMAL(9,2) NOT NULL DEFAULT '0.00',
    `tax_rate`            DECIMAL(5,2) NOT NULL DEFAULT '0.00',
    `definable_field_0`   VARCHAR(150) NULL DEFAULT '',
    `definable_field_1`   VARCHAR(150) NULL DEFAULT '',
    `definable_field_2`   VARCHAR(150) NULL DEFAULT '',
    `link`                TEXT NULL DEFAULT '',
    `description`         TEXT NULL DEFAULT '',
    `full_desc`           TEXT NULL DEFAULT '',
    `modified_when`       INT(11) NOT NULL DEFAULT '0',
    `modified_by`         INT(11) NOT NULL DEFAULT '0',
    `created_when`        INT(11) NOT NULL DEFAULT '0',
    `created_by`          INT(11) NOT NULL DEFAULT '0',
    `seo_title`           VARCHAR(255) NULL DEFAULT '',  /* NEW since vers. 2.0.0 */
    `seo_description`     VARCHAR(255) NULL DEFAULT '',  /* NEW since vers. 2.0.0 */
    PRIMARY KEY (`item_id`)
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_images` (
    `img_id`              INT(11) NOT NULL AUTO_INCREMENT,
    `item_id`             INT(11) NOT NULL DEFAULT '0',
    `item_attribute_id`   INT(11) NOT NULL DEFAULT '0',
    `filename`            VARCHAR(150) NOT NULL DEFAULT '',
    `active`              ENUM('1','0') NOT NULL DEFAULT '1',
    `position`            INT(11)   NOT NULL DEFAULT '0',
    `alt`                 VARCHAR(255)   NULL DEFAULT '',
    `title`               VARCHAR(255) NULL DEFAULT '',
    `caption`             TEXT NULL DEFAULT '',  
    PRIMARY KEY (`img_id`)
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_options` (
    `option_id`           INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `option_name`         VARCHAR(64) NOT NULL DEFAULT ''
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_attributes` (
    `attribute_id`        INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `option_id`           INT(6) NOT NULL DEFAULT 0,
    `attribute_name`      VARCHAR(64) NOT NULL DEFAULT ''
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_item_attributes` (
    `assign_id`           INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `item_id`             INT(6) NOT NULL,
    `option_id`           INT(6) NOT NULL,
    `attribute_id`        INT(6) NOT NULL,
    `price`               DECIMAL(9,2) NOT NULL,
    `operator`            VARCHAR(1) NOT NULL
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_customer` (
    `order_id`              INT(6) NOT NULL AUTO_INCREMENT,
    `order_date`            INT(11) NOT NULL DEFAULT '0',
    `shipping_fee`          DECIMAL(9,2) NOT NULL DEFAULT '0.00',
    `sales_tax`             DECIMAL(9,2) NOT NULL DEFAULT '0.00',
    `submitted`             VARCHAR(20) NOT NULL DEFAULT 'no',
    `transaction_id`        VARCHAR(64) NOT NULL DEFAULT 'none',
    `transaction_status`    VARCHAR(10) NOT NULL DEFAULT 'none',
    `status`                VARCHAR(20) NOT NULL DEFAULT 'none',
    `user_id`               INT(6) NOT NULL DEFAULT '0',
    `cust_company`          VARCHAR(64) NULL DEFAULT NULL,
    `cust_first_name`       VARCHAR(64) NULL DEFAULT NULL,
    `cust_last_name`        VARCHAR(64) NULL DEFAULT NULL,
    `cust_tax_no`           VARCHAR(11) NULL DEFAULT NULL,
    `cust_street`           VARCHAR(64) NULL DEFAULT NULL,
    `cust_street_number`    VARCHAR(24) NOT NULL,            /* NEW since vers. 2.0.0 */
    `cust_address_addition` VARCHAR(255) NOT NULL,           /* NEW since vers. 2.0.0 */
    `cust_city`             VARCHAR(64) NULL DEFAULT NULL,
    `cust_state`            VARCHAR(64) NULL DEFAULT NULL,
    `cust_country`          VARCHAR(2) NULL DEFAULT NULL,
    `cust_zip`              VARCHAR(10) NULL DEFAULT NULL,
    `cust_email`            VARCHAR(64) NULL DEFAULT NULL,
    `cust_phone`            VARCHAR(20) NULL DEFAULT NULL,
    `cust_mobile`           VARCHAR(32) NOT NULL,            /* NEW since vers. 2.0.0 */
    `ship_company`          VARCHAR(64) NULL DEFAULT NULL,
    `ship_first_name`       VARCHAR(64) NULL DEFAULT NULL,
    `ship_last_name`        VARCHAR(64) NULL DEFAULT NULL,
    `ship_street`           VARCHAR(64) NULL DEFAULT NULL,
    `ship_street_number`    VARCHAR(24) NOT NULL,            /* NEW since vers. 2.0.0 */
    `ship_address_addition` VARCHAR(255) NOT NULL,           /* NEW since vers. 2.0.0 */
    `ship_city`             VARCHAR(64) NULL DEFAULT NULL,
    `ship_state`            VARCHAR(64) NULL DEFAULT NULL,
    `ship_country`          VARCHAR(2) NULL DEFAULT NULL,
    `ship_zip`              VARCHAR(10) NULL DEFAULT NULL,
    `ship_phone`            VARCHAR(32) NOT NULL,            /* NEW since vers. 2.0.0 */
    `ship_mobile`           VARCHAR(32) NOT NULL,            /* NEW since vers. 2.0.0 */
    `invoice_id`            INT(6) NOT NULL DEFAULT '0',
    `sent_invoices`         INT(1) NOT NULL DEFAULT '0',
    `invoice`               TEXT NULL DEFAULT NULL,
    `json_order`            TEXT NULL DEFAULT NULL,          /* NEW since vers. 2.0.0 */
    PRIMARY KEY (`order_id`)
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_order` (
    `order_id`            INT(6) NOT NULL AUTO_INCREMENT,
    `item_id`             INT(5) NOT NULL,
    `attributes`          VARCHAR(64) NOT NULL,
    `sku`                 VARCHAR(20) NOT NULL,
    `quantity`            INT(7) NOT NULL,
    `price`               DECIMAL(9,2) NOT NULL,
    `tax_rate`            DECIMAL(5,2) NOT NULL,
    PRIMARY KEY (`order_id`, `item_id`, `attributes`)
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_general_settings` (
    `shop_id`             INT(11) NOT NULL DEFAULT '0',
    `shop_name`           VARCHAR(100) NULL DEFAULT NULL,
    `shop_email`          VARCHAR(64) NULL DEFAULT NULL,
    `pages_directory`     VARCHAR(20) NOT NULL DEFAULT 'bakery',
    `tac_url`             VARCHAR(255) NULL DEFAULT NULL,
    `shop_country`        VARCHAR(2) NOT NULL DEFAULT 'DE',
    `shop_state`          VARCHAR(5) NULL DEFAULT NULL,
    `shipping_form`       VARCHAR(10) NOT NULL DEFAULT 'none',
    `company_field`       ENUM('show','hide') NOT NULL DEFAULT 'hide',
    `state_field`         ENUM('show','hide') NOT NULL DEFAULT 'show',
    `tax_no_field`        ENUM('show','hide') NOT NULL DEFAULT 'hide',
    `tax_group`           VARCHAR(255) NULL DEFAULT NULL,
    `zip_location`        ENUM('inside','end') NOT NULL DEFAULT 'inside',
    `no_revocation`       VARCHAR(64) NOT NULL DEFAULT 'e-goods',
    `cancellation_url`    VARCHAR(255) NULL DEFAULT NULL,
    `privacy_url`         VARCHAR(255) NULL DEFAULT NULL,
    `hide_country`        ENUM('show','hide') NOT NULL DEFAULT 'show',
    `cust_msg`            ENUM('show','hide') NOT NULL DEFAULT 'hide',
    `skip_cart`           ENUM('yes','no') NOT NULL DEFAULT 'no',
    `display_settings`    ENUM('1','0') NOT NULL DEFAULT '0',
    `use_captcha`         ENUM('yes','no') NOT NULL DEFAULT 'no',
    `lightbox_plugin`     VARCHAR(132) NOT NULL DEFAULT 'lightbox2', /* NEW since vers. 2.0.0 */
    `definable_field_0`   VARCHAR(64) NULL DEFAULT NULL,
    `definable_field_1`   VARCHAR(64) NULL DEFAULT NULL,
    `definable_field_2`   VARCHAR(64) NULL DEFAULT NULL,
    `stock_mode`          VARCHAR(10) NOT NULL DEFAULT 'none',
    `stock_limit`         INT(3) NOT NULL DEFAULT '10',
    `out_of_stock_orders` ENUM('1','0') NOT NULL DEFAULT '0',
    `shop_currency`       VARCHAR(3) NOT NULL DEFAULT 'EUR',
    `dec_point`           VARCHAR(1) NOT NULL DEFAULT ',',
    `thousands_sep`       VARCHAR(1) NOT NULL DEFAULT '.',
    `tax_by`              VARCHAR(10) NOT NULL DEFAULT 'country',
    `tax_rate`            DECIMAL(5,2) NOT NULL DEFAULT '19.00',
    `tax_rate1`           DECIMAL(5,2) NOT NULL DEFAULT '7.00',
    `tax_rate2`           DECIMAL(5,2) NOT NULL DEFAULT '0.00',
    `tax_included`        ENUM('included','excluded') NOT NULL DEFAULT 'included',
    `tax_rate_shipping`   DECIMAL(5,2) NOT NULL DEFAULT '0.00',
    `free_shipping`       DECIMAL(7,2) NOT NULL DEFAULT '99999.99',
    `free_shipping_msg`   ENUM('show','hide') NOT NULL DEFAULT 'hide',
    `shipping_method`     VARCHAR(20) NOT NULL DEFAULT 'flat',
    `shipping_domestic`   DECIMAL(6,2) NOT NULL DEFAULT '0',
    `shipping_abroad`     DECIMAL(6,2) NOT NULL DEFAULT '0',
    `shipping_zone`       DECIMAL(6,2) NOT NULL DEFAULT '0.00',
    `zone_countries`      TEXT NULL DEFAULT NULL,
    `use_payment`         TINYINT(1) NOT NULL DEFAULT '1', /* NEW since vers. 2.0.0 */
    `form_config`         TEXT NULL DEFAULT NULL,          /* NEW since vers. 2.0.0 */
    PRIMARY KEY (`shop_id`)
) {TABLE_ENGINE};


CREATE TABLE IF NOT EXISTS `{BXT}_page_settings` (
    `section_id`          INT(11) NOT NULL DEFAULT '0',
    `page_id`             INT(11) NOT NULL DEFAULT '0',
    `page_offline`        ENUM('yes','no') NOT NULL DEFAULT 'no',
    `offline_text`        TINYTEXT NULL DEFAULT NULL,
    `continue_url`        INT(11) NULL DEFAULT NULL,
    `header`              TEXT NULL DEFAULT NULL,
    `item_loop`           TEXT NULL DEFAULT NULL,
    `footer`              TEXT NULL DEFAULT NULL,
    `item_header`         TEXT NULL DEFAULT NULL,
    `item_footer`         TEXT NULL DEFAULT NULL,
    `items_per_page`      INT(11) NOT NULL DEFAULT '0',
    `num_cols`            INT(11) NOT NULL DEFAULT '3',
    `resize`              INT(11) NOT NULL DEFAULT '100',
    `lightbox`            VARCHAR(10) NOT NULL DEFAULT 'detail',
    `layout`              VARCHAR(32) NOT NULL DEFAULT '',
    PRIMARY KEY (`section_id`)
) {TABLE_ENGINE};

CREATE TABLE IF NOT EXISTS `{BXT}_payment_methods` (
    `pm_id`               INT(11) NOT NULL AUTO_INCREMENT,
    `active`              INT(1) NOT NULL,
    `directory`           VARCHAR(64) NOT NULL,
    `name`                VARCHAR(64) NOT NULL,
    `version`             VARCHAR(6) NOT NULL,
    `author`              VARCHAR(64) NOT NULL,
    `requires`            VARCHAR(6) NOT NULL,
    `field_1`             VARCHAR(150) NOT NULL,
    `value_1`             TEXT NULL DEFAULT NULL,
    `field_2`             VARCHAR(150) NOT NULL,
    `value_2`             TEXT NULL DEFAULT NULL,
    `field_3`             VARCHAR(150) NOT NULL,
    `value_3`             TEXT NULL DEFAULT NULL,
    `field_4`             VARCHAR(150) NOT NULL,
    `value_4`             TEXT NULL DEFAULT NULL,
    `field_5`             VARCHAR(150) NOT NULL,
    `value_5`             TEXT NULL DEFAULT NULL,
    `field_6`             VARCHAR(150) NOT NULL,
    `value_6`             TEXT NULL DEFAULT NULL,
    `cust_email_subject`  TEXT NOT NULL,
    `cust_email_body`     TEXT NOT NULL,
    `shop_email_subject`  TEXT NOT NULL,
    `shop_email_body`     TEXT NOT NULL,
    PRIMARY KEY (`pm_id`)
) {TABLE_ENGINE};

-- // NEW since vers. 2.0.0
CREATE TABLE IF NOT EXISTS `{BXT}_requests` (
    `request_id`          INT(6) NOT NULL AUTO_INCREMENT,
    `order_id`            INT(6) NOT NULL,
    `timestamp`           INT(11) NOT NULL,
    `user_id`             INT(6) NOT NULL,
    `first_name`          VARCHAR(64) NOT NULL,
    `last_name`           VARCHAR(64) NOT NULL,
    `email`               VARCHAR(64) NOT NULL,
    `status`              INT(1) NOT NULL,
    `json`                TEXT NOT NULL,          
    PRIMARY KEY (`request_id`)
) {TABLE_ENGINE};