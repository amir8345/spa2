SELECT contributors.* , follow_table.follower_num , book_num_table.book_num

FROM contributors 

LEFT JOIN

(
    SELECT following_id as contributor_id , COUNT(following_id) AS follower_num 
    FROM follows 
    WHERE following_type = 'contributor' 
    GROUP by following_id
) 
AS follow_table

ON contributors.id = follow_table.contributor_id

LEFT JOIN

(
    SELECT contributor_id , COUNT(contributor_id) AS book_num 
    FROM book_contributor 
    GROUP by contributor_id
) 
AS book_num_table

ON contributors.id = book_num_table.contributor_id