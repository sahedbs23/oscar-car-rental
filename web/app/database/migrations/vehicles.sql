CREATE TABLE `vehicles` (
  `id` bigint PRIMARY KEY,
  `location` varchar(100),
  `car_brand` varchar(100),
  `car_model` varchar(100),
  `license_plate` varchar(50),
  `car_year` int,
  `number_of_door` int,
  `number_of_seat` int,
  `fuel_type` varchar(25),
  `transmission` varchar(100),
  `car_group` varchar(100),
  `car_type` varchar(100),
  `car_km` float,
  `inside_height` float,
  `inside_length` float,
  `inside_width` float
);

CREATE TABLE `car_brands` (
  `id` int PRIMARY KEY,
  `brand_name` varchar(255),
  `created_at` datetime
);
