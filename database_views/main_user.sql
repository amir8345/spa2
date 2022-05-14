SELECT users.* , follow_table.follower_num FROM `users` 
LEFT JOIN 

(
    SELECT following_id as user_id , COUNT(following_id) AS follower_num 
    FROM follows 
    WHERE following_type = 'user' 
    GROUP BY following_id
) 
AS follow_table

ON users.id = follow_table.user_id 
WHERE id NOT IN (SELECT user_id FROM contributor_user)
AND 
id NOT IN (SELECT user_id FROM publisher_user)