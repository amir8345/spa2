SELECT users.* , follow_table.follower , book_in_shelves_table.book

FROM `users` 

LEFT JOIN 
(
    SELECT following_id as user_id , COUNT(following_id) AS follower 
    FROM follows 
    WHERE following_type = 'user' 
    GROUP BY following_id
) 
AS follow_table

ON users.id = follow_table.user_id 

LEFT JOIN 

(SELECT shelves.user_id , COUNT(shelves.user_id) AS book
FROM shelves
INNER JOIN book_shelf
ON shelves.id = book_shelf.shelf_id
GROUP BY shelves.user_id )
AS book_in_shelves_table

ON users.id = book_in_shelves_table.user_id
