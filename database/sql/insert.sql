USE travel_service;

INSERT INTO `city` (`name_city`) VALUES
('Moscow'),
('London'),
('Paris'),
('Berlin'),

INSERT INTO `place` (`name_place`, `distance`, `city_id`) VALUES
('Moscow Cathedral', 1.3, 1),
('Moscow Museum', 2.4, 1),
('Moscow Monument', 3.5, 1),
('Moscow Bridge', 1.1, 1),
('Moscow Square', 5.7, 1),
('Moscow Theatre', 16.2, 1),

('London Cathedral', 1.3, 2),
('London Museum', 2.4, 2),
('London Monument', 3.5, 2),
('London Bridge', 1.1, 2),

('Paris Museum', 2.4, 3),
('Paris Bridge', 1.1, 3),
('Paris Square', 5.7, 3),
('Paris Theatre', 16.2, 3),

('Berlin Museum', 2.4, 4),
('Berlin Monument', 3.5, 4),
('Berlin Bridge', 1.1, 4),
('Berlin Square', 5.7, 4),
('Berlin Theatre', 16.2, 4),

INSERT INTO `traveler` (`name_traveler`) VALUES
('Maks'),
('Peter'),
('Sergei'),
('Alex'),
('Mike'),
('August');

