
SELECT publishers.* , book_table.book , follow_table.follower

FROM publishers

LEFT JOIN 

(SELECT books.publisher_id , COUNT(books.publisher_id) book FROM books 

GROUP BY books.publisher_id) AS book_table

ON publishers.id = book_table.publisher_id

LEFT JOIN

(SELECT following_id AS publisher_id , COUNT(following_id) AS follower FROM follows 

WHERE following_type = 'publisher'

GROUP BY following_id) 

AS follow_table

ON publishers.id = follow_table.publisher_id

