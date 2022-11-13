USE travel_service;

INSERT INTO `city` (`name_city`) VALUES
('Moscow'),
('London'),
('Paris'),
('Berlin');

INSERT INTO `place` (`name_place`, `rank`, `distance`, `city_id`) VALUES
('Moscow Cathedral', 6.00, 1.3, 1),
('Moscow Museum', 0.00, 2.4, 1),
('Moscow Monument', 0.00, 3.5, 1),
('Moscow Bridge', 7.00, 1.1, 1),
('Moscow Square', 0.00, 5.7, 1),
('Moscow Theatre', 0.00, 16.2, 1),

('London Cathedral', 0.00, 1.3, 2),
('London Museum', 8.00, 2.4, 2),
('London Monument', 0.00, 3.5, 2),
('London Bridge', 6.50, 1.1, 2),

('Paris Museum', 0.00, 2.4, 3),
('Paris Bridge', 6.00, 1.1, 3),
('Paris Square', 0.00, 5.7, 3),
('Paris Theatre', 0.00, 16.2, 3),

('Berlin Museum', 0.00, 2.4, 4),
('Berlin Monument', 0.00, 3.5, 4),
('Berlin Bridge', 4.50, 1.1, 4),
('Berlin Square', 0.00, 5.7, 4),
('Berlin Theatre', 4.00, 16.2, 4);

INSERT INTO `traveler` (`name_traveler`, `phone`, `password`) VALUES
('Maks', '89991111111', 'Maks'),
('Leonid', '89992222222', 'Leonid'),
('Oleg', '89993333333', 'Oleg');

INSERT INTO `score` (`score`, `traveler_id`, `place_id`) VALUES
(5, 1, 1),
(10, 1, 8),
(9, 1, 10),
(7, 2, 4),
(7, 2, 12),
(7, 2, 17),
(7, 2, 1),
(6, 2, 8),
(4, 2, 10),
(5, 3, 12),
(2, 3, 17),
(4, 3, 19);
