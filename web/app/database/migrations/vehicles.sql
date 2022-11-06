CREATE TABLE `vehicles`
(
    `id`             bigint PRIMARY KEY AUTO_INCREMENT,
    `location`       bigint,
    `car_brand`      bigint,
    `car_model`      bigint,
    `license_plate`  varchar(50) UNIQUE DEFAULT null,
    `car_year`       int                DEFAULT 0,
    `number_of_doors` int                DEFAULT 0,
    `number_of_seats` int                DEFAULT 0,
    `fuel_type`      varchar(100)       DEFAULT null,
    `transmission`   varchar(100)       DEFAULT null,
    `car_type_group`      varchar(100)       DEFAULT null,
    `car_type`       varchar(100)       DEFAULT null,
    `car_km`         float              DEFAULT 0
);

CREATE TABLE `car_locations`
(
    `id`       bigint PRIMARY KEY AUTO_INCREMENT,
    `location` varchar(100) UNIQUE
);

CREATE TABLE `car_brands`
(
    `id`         bigint PRIMARY KEY AUTO_INCREMENT,
    `brand_name` varchar(100) UNIQUE
);

CREATE TABLE `car_models`
(
    `id`        bigint PRIMARY KEY AUTO_INCREMENT,
    `car_model` varchar(100) UNIQUE
);

CREATE TABLE `car_features`
(
    `id`            bigint PRIMARY KEY AUTO_INCREMENT,
    `vehicle_id`    bigint unique,
    `inside_height` float DEFAULT null,
    `inside_length` float DEFAULT null,
    `inside_width`  float DEFAULT null
);

CREATE TABLE `fuels`
(
    `id`   bigint PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) UNIQUE
);

CREATE TABLE `fuel_vehicle`
(
    `vehicle_id` bigint,
    `fuel_id`    bigint
);

ALTER TABLE `fuel_vehicle`
    ADD FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

ALTER TABLE `fuel_vehicle`
    ADD FOREIGN KEY (`fuel_id`) REFERENCES `fuels` (`id`);

ALTER TABLE `car_features`
    ADD FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

ALTER TABLE `vehicles`
    ADD FOREIGN KEY (`location`) REFERENCES `car_locations` (`id`);

ALTER TABLE `vehicles`
    ADD FOREIGN KEY (`car_brand`) REFERENCES `car_brands` (`id`);

ALTER TABLE `vehicles`
    ADD FOREIGN KEY (`car_model`) REFERENCES `car_models` (`id`);
