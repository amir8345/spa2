SELECT books.* , 
score_table.score , 
debate_table.debate ,
popular_table.read ,
popular_table.want ,
popular_table.reading

FROM books 

LEFT JOIN 

(SELECT book_id , round( AVG(score) , 1) AS score FROM scores GROUP BY book_id) AS score_table

ON books.id = score_table.book_id

LEFT JOIN

(SELECT book_id , SUM(num) AS debate from 
(SELECT posted_id AS book_id , COUNT(posted_id) AS num FROM posts WHERE posted_type = 'book' GROUP BY posted_id
UNION ALL
 SELECT commented_id AS book_id , COUNT(commented_id) AS num FROM comments WHERE commented_type = 'book' GROUP BY commented_id) AS post_and_comment
 GROUP BY book_id) AS debate_table
 
ON books.id = debate_table.book_id

LEFT JOIN 

(SELECT book_id , 
max(case when (name='read') then num else 0 end) as 'read',
max(case when (name='want') then num else 0 end) as 'want',
max(case when (name='reading') then num else 0 end) as 'reading'
from 
(SELECT book_id , name , COUNT(name) AS num FROM book_shelf LEFT JOIN shelves ON book_shelf.shelf_id = shelves.id
GROUP BY book_id , name) AS table1 GROUP BY book_id) 

AS popular_table

ON books.id = popular_table.book_id

 
 
 
 
 
 