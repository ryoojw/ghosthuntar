# 1. Spherical law of cosines?
# London
# Latitude:  51.507335
# Longitude: -0.127683
# Radius: 5

SELECT `id`, `name`, `power`, `latitude`, `longitude`, ( 3959 * acos( cos( radians(51.507335) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-0.127683) ) + sin( radians(51.507335) ) * sin( radians( latitude ) ) ) ) AS distance
FROM tbl_ghost
WHERE latitude > (51.507335 - (5 / 69)) AND latitude < (51.507335 + (5 / 69)) AND longitude > (-0.127683 - 5 / abs(cos(radians(51.507335)) * 69)) AND longitude < (-0.127683 + 5 / abs(cos(radians(51.507335)) * 69))

# 2. Spherical law of cosines?
# Nottingham NG7 3LJ
# Latitude:  52.957074
# Longitude: -1.182894
# Radius: 5

SELECT `id`, `name`, `power`, `latitude`, `longitude`, ( 3959 * acos( cos( radians(52.957074) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(-1.182894) ) + sin( radians(52.957074) ) * sin( radians( latitude ) ) ) ) AS distance
FROM tbl_ghost
WHERE latitude > (52.957074 - (5 / 69)) AND latitude < (52.957074 + (5 / 69)) AND longitude > (-1.182894 - 5 / abs(cos(radians(52.957074)) * 69)) AND longitude < (-1.182894 + 5 / abs(cos(radians(52.957074)) * 69))
