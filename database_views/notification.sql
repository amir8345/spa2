SELECT 
(
    CASE 
	WHEN (liked_type = 'comment') 
   		THEN( SELECT user_id from comments WHERE id = liked_id )
	WHEN (liked_type = 'post') 
   		THEN (SELECT user_id from posts WHERE id = liked_id)
	END 
) AS user_id , 
 
 'like' AS 'event' , likes.user_id as maker_id , 
 liked_type AS event_subject , liked_id AS event_id , created_at as time
 FROM likes

UNION ALL

SELECT * FROM 
(SELECT 
(
    CASE 
    WHEN commented_type = 'user' THEN commented_id
    WHEN commented_type = 'post' THEN 
    (SELECT user_id FROM posts WHERE posts.id = b.commented_id)
    WHEN commented_type = 'comment' THEN
    (SELECT user_id FROM comments a WHERE a.id = b.commented_id)
    END
) AS user_id , 

'comment' as 'event' , user_id as maker_id , commented_type AS event_subject, commented_id AS event_id , created_at as time

 FROM comments b ) AS table1
 WHERE table1.user_id IS NOT NULL

UNION ALL

SELECT posted_id as user_id , 'post' as 'event' , user_id as maker_id , 
'user' as event_subject , posted_id as event_id , created_at as time
FROM posts 
WHERE posted_type = 'user'

UNION ALL

SELECT receiver AS user_id , 'suggestion' AS 'event' , user_id AS maker_id , 'book' AS event_subject , book_id AS event_id , created_at as time
FROM suggestions

UNION ALL

SELECT follower_id as user_id , 'accept' as 'event' , following_id as 'maker_id' , 'user' as 'event_subject' , following_id as 'event_id' , created_at as time
FROM follows
WHERE following_type = 'user'
AND
status = 'a'
AND
updated_at > created_at
AND
following_id IN (
SELECT id FROM users where private = true
)
order by time
