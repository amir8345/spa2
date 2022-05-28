
SELECT user_id , MAX(updated_at) AS last_update
FROM
(SELECT shelves.user_id,  shelves.updated_at
FROM shelves

UNION ALL 

SELECT shelves.user_id , book_shelf.updated_at
FROM shelves
LEFT JOIN book_shelf
ON shelves.id = book_shelf.shelf_id) AS table_1
GROUP BY user_id