SELECT contributors.* , contributor_user.user_id , user_followers_table.follower , book_num_table.book
FROM contributors 
LEFT JOIN
contributor_user
ON contributors.id = contributor_user.contributor_id

LEFT JOIN 

(SELECT following_id AS user_id , COUNT(follower_id) AS follower
FROM follows WHERE following_type = 'user' GROUP BY following_type , following_id) AS user_followers_table

ON contributor_user.user_id = user_followers_table.user_id

LEFT JOIN

(
    SELECT contributor_id , COUNT(contributor_id) AS book
    FROM book_contributor 
    GROUP by contributor_id
) 
AS book_num_table

ON contributors.id = book_num_table.contributor_id