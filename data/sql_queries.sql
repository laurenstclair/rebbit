/**
SQL SCRIPTS FOR Rebbit
**/

-- Get Posts in a Knot
SELECT *
FROM Posts
WHERE knot_id = ?;

SELECT *
FROM Posts AS p, Knot AS k
WHERE p.knot_id = k.knot_id
    AND p.knot_id = ?;

-- Get Posts on Dash
SELECT *
FROM Posts AS p, Knot AS k
WHERE p.knot_id = k.knot_id
    AND knot_id IN(SELECT knot_id
                   FROM FollowingKnot
                   WHERE user_id = ?);

-- Get Comments on a Post
SELECT comment_id
FROM comment_id
WHERE post_id = ?;

-- TODO: Comments on comments


